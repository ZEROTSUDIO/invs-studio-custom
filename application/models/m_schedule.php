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
        // Load config
        $slack_buffer        = $this->config->item('urgency_slack_buffer')      ?: 0.25;
        $quick_threshold     = $this->config->item('quick_insert_threshold')     ?: 240;
        $quick_deadline_days = $this->config->item('quick_insert_deadline_days') ?: 2;

        // 1. Fetch all schedulable orders (waiting or previously scheduled)
        $orders = $this->db
            ->where_in('status', ['waiting', 'scheduled'])
            ->get('orders')
            ->result();

        if (!$orders) return;

        // Get anchor time: end of the latest in_progress job
        $in_progress = $this->db
            ->where('status', 'in_progress')
            ->order_by('end_date', 'DESC')
            ->limit(1)
            ->get('production_schedule')
            ->row();

        if ($in_progress) {
            $anchor = $this->force_business_hours($in_progress->end_date);
            $queue  = $in_progress->queue_position + 1;
        } else {
            $anchor = $this->force_business_hours(date('Y-m-d H:i:s'));
            $queue  = 1;
        }

        // 2. Three-tier classification
        //
        //    Tier 1 — URGENT (EDF)
        //      Worst-case finish exceeds deadline slack. Must run first.
        //
        //    Tier 2 — QUICK-INSERT (pause-slot)
        //      Small job (≤ threshold) with a near deadline that fits in
        //      today's remaining business hours. Workers pause the long
        //      in-progress batch, knock out the quick job, then resume.
        //      Runs right after urgent jobs, before the normal SJF pile.
        //
        //    Tier 3 — NORMAL (SJF)
        //      Everything else — sorted shortest-first for throughput.
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
            // --- Tier 2: Quick-Insert check (evaluated BEFORE urgency) ---
            // Must be: small enough, due soon (but NOT overdue), and fits in today.
            // Overdue jobs skip this and fall through to the urgency check below.
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

            // --- Tier 1 & 3: Queue-aware urgency check (existing logic) ---
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

        // 3. Sort each tier
        //    Urgent       → Earliest Deadline First (protect hard deadlines)
        usort($urgent,       fn($a, $b) => strtotime($a->deadline) - strtotime($b->deadline));
        //    Quick-Insert  → Shortest First (maximise throughput in the pause slot)
        usort($quick_insert, fn($a, $b) => $a->est_duration - $b->est_duration);
        //    Normal        → Shortest Job First (classic SJF throughput)
        usort($normal,       fn($a, $b) => $a->est_duration - $b->est_duration);

        // 4. Delete old unstarted scheduled entries
        $this->db->where('status', 'scheduled')->delete('production_schedule');

        $this->db->trans_start();

        // 5a. QUICK-INSERT jobs start from NOW (pause-slot model).
        //     Workers pause the in_progress batch, handle these today, then resume.
        //     These get the LOWEST queue positions because they run FIRST, today.
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
                ]);

                $this->db->where('id', $o->id)->update('orders', ['status' => 'scheduled']);

                $quick_time = $end;
                $queue++;
            }
        }

        // 5b. URGENT + NORMAL jobs start from the anchor (after in_progress ends).
        $current_time = $anchor;
        foreach (array_merge($urgent, $normal) as $o) {
            $start = $current_time;
            $end   = $this->advance_time($start, $o->est_duration);

            $this->db->insert('production_schedule', [
                'order_id'       => $o->id,
                'queue_position' => $queue,
                'start_date'     => $start,
                'end_date'       => $end,
                'status'         => 'scheduled',
            ]);

            $this->db->where('id', $o->id)->update('orders', ['status' => 'scheduled']);

            $current_time = $end;
            $queue++;
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Returns the earliest SAFE deadline date for a new order.
     *
     * Quick-Insert fast path:
     *   If the job is small (≤ quick_insert_threshold) AND fits in today's
     *   remaining business hours, it will be slotted into the pause-slot tier
     *   on the next generate(). We calculate its finish from NOW instead of
     *   the far queue tail — giving the customer an accurate, near-term promise.
     *   The normal safety buffer still applies (Q3: no bypass of the deadline check).
     *
     * Standard path:
     *   Queue-tail projection + safety buffer (original logic, unchanged).
     */
    public function get_earliest_deadline($est_duration_mins)
    {
        $slack_buffer        = $this->config->item('urgency_slack_buffer')      ?: 0.25;
        $quick_threshold     = $this->config->item('quick_insert_threshold')     ?: 240;

        // --- Quick-Insert fast path ---
        $remaining_today = $this->remaining_today_minutes();
        if ($est_duration_mins <= $quick_threshold && $remaining_today >= $est_duration_mins) {
            // Start from NOW (clamped to business hours) — not from the queue tail
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

        // --- Standard queue-tail path ---

        // 1. Find the anchor (end of the currently generated schedule)
        $latest = $this->db
            ->select_max('end_date')
            ->where_in('status', ['scheduled', 'in_progress'])
            ->get('production_schedule')
            ->row();

        if ($latest && $latest->end_date) {
            $anchor = $this->force_business_hours($latest->end_date);
        } else {
            $anchor = $this->force_business_hours(date('Y-m-d H:i:s'));
        }

        // 2. Account for 'waiting' orders not yet in production_schedule
        $waiting_orders = $this->db
            ->select_sum('est_duration')
            ->where('status', 'waiting')
            ->get('orders')
            ->row();

        $waiting_mins = $waiting_orders ? (int)$waiting_orders->est_duration : 0;

        // 3. Project when THIS new job would finish
        $queue_tail    = $this->advance_time($anchor, $waiting_mins);
        $projected_end = $this->advance_time($queue_tail, $est_duration_mins);

        // 4. Add safety buffer
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
        $this->db->select('ps.*, o.order_code, o.qty, o.est_duration, c.name as customer_name');
        $this->db->from('production_schedule ps');
        $this->db->join('orders o', 'o.id = ps.order_id');
        $this->db->join('customers c', 'c.id = o.customer_id');
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
     * Returns remaining operational minutes in today's business hours (08:30–17:00).
     * Returns 0 on Sundays or after closing time.
     * Mirrors the get_remaining_today_minutes() helper in app_helper.php.
     */
    private function remaining_today_minutes()
    {
        $now = new DateTime();
        if ($now->format('w') == 0) return 0;

        $now_mins   = (int)$now->format('G') * 60 + (int)$now->format('i');
        $start_mins = 8 * 60 + 30;  // 510
        $end_mins   = 17 * 60;       // 1020

        if ($now_mins >= $end_mins)  return 0;
        if ($now_mins < $start_mins) return $end_mins - $start_mins;

        return $end_mins - $now_mins;
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

    /**
     * Pseudo-cron: Automatically update statuses based on the current time.
     * scheduled -> in_progress (if start_date <= NOW)
     * in_progress -> done/completed (if end_date <= NOW)
     */
    public function auto_update_statuses()
    {
        $now = date('Y-m-d H:i:s');
        
        // 1. Move scheduled -> in_progress
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
        
        // 2. Move in_progress -> done
        $this->db->select('order_id, id as schedule_id');
        $this->db->where('status', 'in_progress');
        $this->db->where('end_date <=', $now);
        $to_finish = $this->db->get('production_schedule')->result();
        
        if (!empty($to_finish)) {
            $order_ids = array_map(fn($item) => $item->order_id, $to_finish);
            $schedule_ids = array_map(fn($item) => $item->schedule_id, $to_finish);
            
            $this->db->where_in('id', $order_ids)->update('orders', ['status' => 'done']);
            $this->db->where_in('id', $schedule_ids)->update('production_schedule', ['status' => 'completed']);
        }
    }
}
