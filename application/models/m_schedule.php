<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_schedule extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_settings');
    }

    //konsep
    //1. ambil order yang bisa dijadwalkan (selagi bukan finished)
    //2. pisahkan urgent vs normal
    //3. sorting
    //4. HAPUS schedule lama (hanya yang belum jalan)
    //5. ambil job yang sedang jalan (kalau ada)
    //6. generate schedule baru

    public function generate()
    {
        // Ambil pengaturan dari DB
        $slack_buffer        = (float)$this->m_settings->get('urgency_slack_buffer', 0.25);
        $quick_threshold     = (int)$this->m_settings->get('quick_insert_threshold', 480);
        $quick_deadline_days = (int)$this->m_settings->get('quick_insert_deadline_days', 2);

        // 1. Ambil semua pesanan yang bisa dijadwalkan (hanya yang berstatus waiting dan scheduled)
        $orders = $this->db
            ->where_in('status', ['waiting', 'scheduled'])
            ->get('orders')
            ->result();

        // 1b. Hitung ulang tanggal selesai untuk pekerjaan yang sedang berjalan jika durasinya diubah
        // Jika tidak dilakukan, perubahan durasi tidak akan menggeser waktu mulai untuk pesanan berikutnya.
        $in_progress_data = $this->db
            ->select('ps.id, ps.start_date, o.est_duration')
            ->from('production_schedule ps')
            ->join('orders o', 'o.id = ps.order_id')
            ->where('ps.status', 'in_progress')
            ->get()
            ->result();

        foreach ($in_progress_data as $ip) {
            $new_end = $this->advance_time($ip->start_date, $ip->est_duration);
            $this->db->where('id', $ip->id)->update('production_schedule', ['end_date' => $new_end]);
        }

        if (!$orders && empty($in_progress_data)) return;

        // Tentukan waktu acuan (anchor): waktu selesai dari pekerjaan terakhir yang sedang berjalan
        $latest_in_progress = $this->db
            ->where('status', 'in_progress')
            ->order_by('end_date', 'DESC')
            ->limit(1)
            ->get('production_schedule')
            ->row();

        if ($latest_in_progress) {
            $anchor = $this->force_business_hours($latest_in_progress->end_date);
        } else {
            $anchor = $this->force_business_hours(date('Y-m-d H:i:s'));
        }

        // Penentuan urutan antrian berikutnya akan dilakukan di dalam blok transaksi 
        // agar antrian dapat di-reset mulai dari 1.

        // 2. Klasifikasi tiga tingkat (Tier)
        //
        //    Tier 1 — MENDESAK (EDF)
        //      Selesai paling lambat melebihi batas toleransi tenggat. Harus dijalankan pertama.
        //
        //    Tier 2 — INSERT CEPAT (slot jeda)
        //      Pekerjaan kecil (≤ ambang batas) dengan tenggat dekat yang muat di
        //      sisa jam kerja hari ini. Pekerja menghentikan sementara batch besar,
        //      selesaikan pekerjaan cepat ini, lalu lanjut kembali.
        //      Berjalan tepat setelah pekerjaan mendesak, sebelum tumpukan SJF normal.
        //
        //    Tier 3 — NORMAL (SJF)
        //      Selain itu — diurutkan berdasarkan durasi tersingkat untuk produktivitas.
        //
        $total_mins = array_sum(array_column(
            array_map(fn($o) => (array)$o, $orders),
            'est_duration'
        ));

        $remaining_today       = $this->remaining_today_minutes();
        $quick_deadline_cutoff = date('Y-m-d', strtotime("+{$quick_deadline_days} days"));

        $urgent       = [];
        $quick_insert = [];
        $normal       = [];

        $today = date('Y-m-d');

        foreach ($orders as $o) {
            // --- Tier 2: Cek Insert Cepat (dievaluasi SEBELUM mendesak) ---
            // Syarat: cukup kecil, segera tenggat (tapi BELUM terlewat), dan muat hari ini.
            // Pekerjaan yang sudah terlewat akan masuk ke pengecekan mendesak di bawah.
            $is_small   = ($o->est_duration <= $quick_threshold);
            $is_near    = (
                !empty($o->deadline) &&
                $o->deadline >= $today &&               // not already overdue
                $o->deadline <= $quick_deadline_cutoff  // due within N days
            );
            $fits_today = ($remaining_today >= $o->est_duration);

            if ($is_small && $is_near && $fits_today) {
                $quick_insert[] = $o;
                continue;
            }

            // --- Tier 1 & 3: Pengecekan mendesak berbasis antrian (logika lama) ---
            $others_mins   = $total_mins - $o->est_duration;
            $worst_start   = $this->advance_time($anchor, $others_mins);
            $worst_end     = $this->advance_time($worst_start, $o->est_duration);
            $slack_mins    = (strtotime($o->deadline) - strtotime($worst_end)) / 60;
            $buffer_needed = $o->est_duration * $slack_buffer;

            if ($slack_mins < $buffer_needed) {
                $urgent[] = $o;
            } else {
                $normal[] = $o;
            }
        }

        // 3. Urutkan setiap Tier
        //    Mendesak       → Tenggat paling awal dulu, lalu Durasi tersingkat
        usort($urgent, function($a, $b) {
            $da = strtotime($a->deadline ?: '9999-12-31');
            $db = strtotime($b->deadline ?: '9999-12-31');
            if ($da != $db) return $da - $db;
            return $a->est_duration - $b->est_duration;
        });
        //    Insert Cepat  → Durasi tersingkat (memaksimalkan hasil di slot jeda)
        usort($quick_insert, fn($a, $b) => $a->est_duration - $b->est_duration);
        
        //    Normal        → Tenggat dulu, lalu Durasi tersingkat
        //    Ini memastikan pesanan bulan ini diprioritaskan di atas pesanan bulan depan.
        usort($normal, function($a, $b) {
            $da = strtotime($a->deadline ?: '9999-12-31');
            $db = strtotime($b->deadline ?: '9999-12-31');
            if ($da != $db) return $da - $db;
            return $a->est_duration - $b->est_duration;
        });

        // 4. Hapus entri jadwal lama yang belum dimulai
        $this->db->where('status', 'scheduled')->delete('production_schedule');

        $this->db->trans_start();

        // 4b. Padatkan nomor antrian yang sedang berjalan agar selalu mulai dari 1
        $in_progress_jobs = $this->db
            ->where('status', 'in_progress')
            ->order_by('queue_position', 'ASC')
            ->get('production_schedule')
            ->result();

        $queue = 1;
        foreach ($in_progress_jobs as $ip) {
            $this->db->where('id', $ip->id)->update('production_schedule', ['queue_position' => $queue]);
            $queue++;
        }

        // 5a. Pekerjaan INSERT CEPAT mulai dari SEKARANG (model slot jeda).
        //     Pekerja menjeda batch yang sedang berjalan, selesaikan ini hari ini, lalu lanjut.
        //     Ini mendapat posisi antrian TERENDAH karena dijalankan PERTAMA, hari ini.
        if (!empty($quick_insert)) {
            $quick_time = $this->force_business_hours(date('Y-m-d H:i:s'));
            foreach ($quick_insert as $o) {
                $start = $quick_time;
                $end   = $this->advance_time($start, $o->est_duration);

                $this->db->insert('production_schedule', [
                    'order_id'       => $o->id,
                    'queue_position' => $queue,
                    'start_date'     => $start,
                    'end_date'       => $end,
                    'status'         => 'scheduled',
                    'schedule_tier'  => 'quick_insert',
                ]);

                $this->db->where('id', $o->id)->update('orders', ['status' => 'scheduled']);

                $quick_time = $end;
                $queue++;
            }
        }

        $current_time = $anchor;
        
        // Prioritas berikutnya: Tier MENDESAK
        foreach ($urgent as $o) {
            $start = $current_time;
            $end   = $this->advance_time($start, $o->est_duration);

            $this->db->insert('production_schedule', [
                'order_id'       => $o->id,
                'queue_position' => $queue,
                'start_date'     => $start,
                'end_date'       => $end,
                'status'         => 'scheduled',
                'schedule_tier'  => 'urgent',
            ]);

            $this->db->where('id', $o->id)->update('orders', ['status' => 'scheduled']);

            $current_time = $end;
            $queue++;
        }

        // Prioritas terakhir: Tier NORMAL (SJF)
        foreach ($normal as $o) {
            $start = $current_time;
            $end   = $this->advance_time($start, $o->est_duration);

            $this->db->insert('production_schedule', [
                'order_id'       => $o->id,
                'queue_position' => $queue,
                'start_date'     => $start,
                'end_date'       => $end,
                'status'         => 'scheduled',
                'schedule_tier'  => 'normal',
            ]);

            $this->db->where('id', $o->id)->update('orders', ['status' => 'scheduled']);

            $current_time = $end;
            $queue++;
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Mengembalikan tanggal tenggat AMAN paling awal untuk pesanan baru.
     *
     * Jalur cepat Insert Cepat:
     *   Jika pekerjaan kecil (≤ ambang batas) DAN muat di sisa jam kerja hari ini,
     *   pekerjaan ini akan dimasukkan ke tier slot jeda pada generate() berikutnya.
     *   Kami menghitung selesainya dari SEKARANG alih-alih dari ujung antrian —
     *   memberikan janji waktu yang akurat kepada pelanggan.
     *   Buffer keamanan normal tetap berlaku.
     *
     * Jalur standar:
     *   Proyeksi ujung antrian + buffer keamanan (logika asli).
     */
    public function get_earliest_deadline($est_duration_mins)
    {
        $slack_buffer    = (float)$this->m_settings->get('urgency_slack_buffer', 0.25);
        $quick_threshold = (int)$this->m_settings->get('quick_insert_threshold', 480);

        // --- Jalur cepat Insert Cepat ---
        $remaining_today = $this->remaining_today_minutes();
        if ($est_duration_mins <= $quick_threshold && $remaining_today >= $est_duration_mins) {
            // Mulai dari SEKARANG (dibatasi jam kerja) — bukan dari ujung antrian
            $now           = $this->force_business_hours(date('Y-m-d H:i:s'));
            $projected_end = $this->advance_time($now, $est_duration_mins);
            $buffer_mins   = (int)ceil($est_duration_mins * $slack_buffer);
            $safe_deadline = $this->advance_time($projected_end, $buffer_mins);

            return [
                'earliest_date'    => date('Y-m-d', strtotime($safe_deadline)),
                'queue_tail'       => $now,
                'projected_finish' => $projected_end,
                'waiting_mins'     => 0,
                'is_quick_insert'  => true,
            ];
        }

        // --- Jalur standar ujung antrian ---

        // 1. Temukan waktu acuan (akhir dari jadwal tetap/prioritas)
        // Kami MENGABAIKAN pekerjaan tier 'normal' karena bersifat fleksibel.
        // Kita hanya memblokir pada pekerjaan yang sedang berjalan dan Mendesak/Insert Cepat.
        $latest = $this->db
            ->select_max('end_date')
            ->group_start()
                ->where('status', 'in_progress')
                ->or_group_start()
                    ->where('status', 'scheduled')
                    ->where_in('schedule_tier', ['urgent', 'quick_insert'])
                ->group_end()
            ->group_end()
            ->get('production_schedule')
            ->row();

        if ($latest && $latest->end_date) {
            $anchor = $this->force_business_hours($latest->end_date);
        } else {
            $anchor = $this->force_business_hours(date('Y-m-d H:i:s'));
        }

        // 2. Perhitungkan pesanan 'waiting' yang belum ada di jadwal produksi
        // Hanya hitung yang berpotensi 'Mendesak' (tenggat segera).
        $waiting_orders = $this->db
            ->where('status', 'waiting')
            ->get('orders')
            ->result();
        
        $waiting_mins = 0;
        $urgency_threshold = strtotime("+3 days"); // Anggap apapun yang tenggat dalam 3 hari sebagai penghalang

        foreach ($waiting_orders as $wo) {
            $deadline_ts = !empty($wo->deadline) ? strtotime($wo->deadline) : 0;
            if ($deadline_ts > 0 && $deadline_ts <= $urgency_threshold) {
                $waiting_mins += (int)$wo->est_duration;
            }
        }

        // 3. Proyeksi kapan pekerjaan baru INI akan selesai
        $queue_tail    = $this->advance_time($anchor, $waiting_mins);
        $projected_end = $this->advance_time($queue_tail, $est_duration_mins);

        // 4. Tambah buffer keamanan
        $buffer_mins   = (int)ceil($est_duration_mins * $slack_buffer);
        $safe_deadline = $this->advance_time($projected_end, $buffer_mins);

        return [
            'earliest_date'    => date('Y-m-d', strtotime($safe_deadline)),
            'queue_tail'       => $queue_tail,
            'projected_finish' => $projected_end,
            'waiting_mins'     => $waiting_mins,
            'is_quick_insert'  => false,
        ];
    }

    public function get_full_schedule()
    {
        $this->db->select('ps.*, o.order_code, cat.name as category_name, o.qty, o.est_duration, o.deadline, c.name as customer_name');
        $this->db->from('production_schedule ps');
        $this->db->join('orders o', 'o.id = ps.order_id');
        $this->db->join('customers c', 'c.id = o.customer_id');
        $this->db->join('categories cat', 'o.category_id = cat.id', 'left');
        $this->db->where_in('ps.status', ['scheduled', 'in_progress']);
        $this->db->order_by('ps.queue_position', 'ASC');
        return $this->db->get()->result();
    }

    public function get_queue_stats($schedules)
    {
        $shortest = null;
        $longest = null;
        $total_dur = 0;
        $count = count($schedules);

        foreach ($schedules as $s) {
            if ($shortest === null || $s->est_duration < $shortest) $shortest = $s->est_duration;
            if ($longest === null || $s->est_duration > $longest) $longest = $s->est_duration;
            $total_dur += $s->est_duration;
        }

        $avg = $count > 0 ? round($total_dur / $count, 1) : 0;
        $load = $count > 10 ? 'High' : ($count > 5 ? 'Moderate' : 'Low');
        $load_color = $count > 10 ? '#f87171' : ($count > 5 ? '#e8a020' : '#4ade80');

        return (object)[
            'count' => $count,
            'shortest' => $shortest,
            'longest' => $longest,
            'avg' => $avg,
            'load' => $load,
            'load_color' => $load_color
        ];
    }

    /**
     * Mengembalikan sisa menit operasional dalam jam kerja hari ini.
     * Jika sudah tutup atau hari Minggu, mengembalikan 0.
     */
    private function remaining_today_minutes()
    {
        $now = new DateTime();
        if ($now->format('w') == 0) return 0;

        $now_mins = (int)$now->format('G') * 60 + (int)$now->format('i');
        
        // Ambil jam operasional dinamis
        list($h_s, $m_s) = explode(':', $this->m_settings->get('business_hour_start', '08:30'));
        list($h_e, $m_e) = explode(':', $this->m_settings->get('business_hour_end', '17:00'));
        $start_mins = (int)$h_s * 60 + (int)$m_s;
        $end_mins   = (int)$h_e * 60 + (int)$m_e;

        if ($now_mins >= $end_mins)  return 0;
        if ($now_mins < $start_mins) return $end_mins - $start_mins;

        return $end_mins - $now_mins;
    }

    public function force_business_hours($datetime_str)
    {
        return $this->advance_time($datetime_str, 0);
    }

    private function advance_time($current_time_str, $add_minutes)
    {
        $dt = new DateTime($current_time_str);
        
        // Ambil jam operasional dinamis
        list($h_s, $m_s) = explode(':', $this->m_settings->get('business_hour_start', '08:30'));
        list($h_e, $m_e) = explode(':', $this->m_settings->get('business_hour_end', '17:00'));
        $start_h = (int)$h_s; $start_m = (int)$m_s;
        $start_min = $start_h * 60 + $start_m;
        $end_min   = (int)$h_e * 60 + (int)$m_e;

        while ($add_minutes > 0 || $this->needs_shift($dt)) {
            if ($dt->format('w') == 0) { // Hari Minggu
                $dt->modify('+1 day')->setTime($start_h, $start_m, 0);
                continue;
            }

            $current_total_min = (int)$dt->format('G') * 60 + (int)$dt->format('i');

            if ($current_total_min < $start_min) {
                $dt->setTime($start_h, $start_m, 0);
                $current_total_min = $start_min;
            } elseif ($current_total_min >= $end_min) {
                $dt->modify('+1 day')->setTime($start_h, $start_m, 0);
                continue;
            }

            if ($add_minutes == 0) break;

            $available_today = $end_min - $current_total_min;

            if ($add_minutes <= $available_today) {
                $dt->modify("+{$add_minutes} minutes");
                $add_minutes = 0;
            } else {
                $add_minutes -= $available_today;
                $dt->modify('+1 day')->setTime($start_h, $start_m, 0);
            }
        }
        return $dt->format('Y-m-d H:i:s');
    }

    private function needs_shift($dt)
    {
        if ($dt->format('w') == 0) return true;

        list($h_s, $m_s) = explode(':', $this->m_settings->get('business_hour_start', '08:30'));
        list($h_e, $m_e) = explode(':', $this->m_settings->get('business_hour_end', '17:00'));
        $start_min = (int)$h_s * 60 + (int)$m_s;
        $end_min   = (int)$h_e * 60 + (int)$m_e;

        $total_min = (int)$dt->format('G') * 60 + (int)$dt->format('i');
        if ($total_min < $start_min || $total_min >= $end_min) return true;
        return false;
    }

    /**
     * Pseudo-cron: Perbarui status secara otomatis berdasarkan waktu sekarang.
     * scheduled -> in_progress (jika tanggal mulai <= SEKARANG)
     * in_progress -> done/completed (jika tanggal selesai <= SEKARANG)
     */
    public function auto_update_statuses()
    {
        $now = date('Y-m-d H:i:s');
        
        // 1. Pindahkan scheduled -> in_progress
        $this->db->select('order_id, id as schedule_id');
        $this->db->where('status', 'scheduled');
        $this->db->where('start_date <=', $now);
        $to_start = $this->db->get('production_schedule')->result();
        
        if (!empty($to_start)) {
            $order_ids = array_map(fn($item) => $item->order_id, $to_start);
            $schedule_ids = array_map(fn($item) => $item->schedule_id, $to_start);
            
            $this->db->where_in('id', $order_ids)->update('orders', ['status' => 'in_progress']);
            $this->db->where_in('id', $schedule_ids)->update('production_schedule', ['status' => 'in_progress']);
        }
        
        // 2. Pindahkan in_progress -> done
        $this->db->select('order_id, id as schedule_id');
        $this->db->where('status', 'in_progress');
        $this->db->where('end_date <=', $now);
        $to_finish = $this->db->get('production_schedule')->result();
        
        if (!empty($to_finish)) {
            $order_ids = array_map(fn($item) => $item->order_id, $to_finish);
            $schedule_ids = array_map(fn($item) => $item->schedule_id, $to_finish);
            
            $this->db->where_in('id', $order_ids)->update('orders', ['status' => 'done']);
            $this->db->where_in('id', $schedule_ids)->update('production_schedule', ['status' => 'completed', 'queue_position' => 0]);
        }
    }
}
