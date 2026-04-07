<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_schedule extends CI_Model
{
    //concept
    //1. ambil order yang bisa dijadwalkan (selagi bukan finished)
    //2. pisahkan urgent vs normal
    //3. sorting
    //4. HAPUS schedule lama (hanya yang belum jalan)
    //5. ambil job yang sedang jalan (kalau ada)
    //6. generate schedule baru

    public function generate()
    {
        // 1. ambil order yang bisa dijadwalkan
        $orders = $this->db
            ->where_in('status', ['waiting', 'scheduled'])
            ->get('orders')
            ->result();

        if (!$orders) return;

        // 2. pisahkan urgent vs normal
        $now = date('Y-m-d H:i:s');

        $urgent = [];
        $normal = [];

        foreach ($orders as $o) {
            $remaining = (strtotime($o->deadline) - strtotime($now)) / 60; // in minutes

            if ($remaining <= ($o->est_duration * 1.5)) {
                $urgent[] = $o;
            } else {
                $normal[] = $o;
            }
        }

        // 3. sorting
        usort($urgent, function ($a, $b) {
            return strtotime($a->deadline) - strtotime($b->deadline);
        });

        usort($normal, function ($a, $b) {
            return $a->est_duration - $b->est_duration;
        });

        $final = array_merge($urgent, $normal);

        // 4. HAPUS schedule lama (hanya yang belum jalan)
        $this->db->where('status', 'scheduled')->delete('production_schedule');

        // 5. ambil job yang sedang jalan (kalau ada)
        $current = $this->db
            ->where('status', 'in_progress')
            ->order_by('start_date', 'DESC')
            ->get('production_schedule')
            ->row();

        if ($current) {
            $current_time = $this->force_business_hours($current->end_date);
            $queue = $current->queue_position + 1;
        } else {
            $current_time = $this->force_business_hours(date('Y-m-d H:i:s'));
            $queue = 1;
        }

        // 6. generate schedule baru
        $this->db->trans_start();

        foreach ($final as $o) {
            $start = $current_time;
            // est_duration is presumably in days in dashboard, but in minutes here? Wait, let's look at est_duration in DB.
            // Oh, user wrote "$start +{$o->est_duration} minutes", but order form uses Days. Wait!
            // Dashboard new_order says "est_duration" is from option values "1", "2", "5" etc (meaning days).
            // Let me adjust the generation to use "days" or if user meant minutes... "est_duration" previously was just raw int. I'll use "+{$o->est_duration} days".
            // WAIT, looking at line 55 of original user code: "start +{$o->est_duration} minutes". If I change it to days, I need to adjust it to days!
            // If it's days, in step 2: `$remaining <= ($o->est_duration * 24 * 60)`. Let's assume it is in Days.
            
            // Calculate end time conforming to 08:30-17:00 and skipping Sundays
            $end = $this->advance_time($start, $o->est_duration);

            // save
            $this->db->insert('production_schedule', [
                'order_id' => $o->id,
                'queue_position' => $queue,
                'start_date' => $start,
                'end_date' => $end,
                'status' => 'scheduled'
            ]);

            // update order status to scheduled
            $this->db->where('id', $o->id)->update('orders', [
                'status' => 'scheduled'
            ]);

            $current_time = $end;
            $queue++;
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_full_schedule()
    {
        $this->db->select('ps.*, o.order_code, o.qty, o.est_duration, c.name as customer_name');
        $this->db->from('production_schedule ps');
        $this->db->join('orders o', 'o.id = ps.order_id');
        $this->db->join('customers c', 'c.id = o.customer_id');
        $this->db->where_in('ps.status', ['scheduled', 'in_progress']);
        $this->db->order_by('ps.queue_position', 'ASC');
        return $this->db->get()->result();
    }

    private function force_business_hours($datetime_str)
    {
        return $this->advance_time($datetime_str, 0);
    }

    private function advance_time($current_time_str, $add_minutes)
    {
        $dt = new DateTime($current_time_str);
        
        while ($add_minutes > 0 || $this->needs_shift($dt)) {
            if ($dt->format('w') == 0) { // Sunday
                $dt->modify('+1 day')->setTime(8, 30, 0);
                continue;
            }

            $current_total_min = (int)$dt->format('G') * 60 + (int)$dt->format('i');
            $start_min = 8 * 60 + 30; // 510 = 08:30
            $end_min = 17 * 60;       // 1020 = 17:00

            if ($current_total_min < $start_min) {
                $dt->setTime(8, 30, 0);
                $current_total_min = $start_min;
            } elseif ($current_total_min >= $end_min) {
                $dt->modify('+1 day')->setTime(8, 30, 0);
                continue;
            }

            if ($add_minutes == 0) break;

            $available_today = $end_min - $current_total_min;

            if ($add_minutes <= $available_today) {
                $dt->modify("+{$add_minutes} minutes");
                $add_minutes = 0;
            } else {
                $add_minutes -= $available_today;
                $dt->modify('+1 day')->setTime(8, 30, 0);
            }
        }
        return $dt->format('Y-m-d H:i:s');
    }

    private function needs_shift($dt)
    {
        if ($dt->format('w') == 0) return true;
        $total_min = (int)$dt->format('G') * 60 + (int)$dt->format('i');
        if ($total_min < (8*60+30) || $total_min >= (17*60)) return true;
        return false;
    }
}
