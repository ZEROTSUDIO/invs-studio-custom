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
        // Load slack buffer config
        $slack_buffer = $this->config->item('urgency_slack_buffer') ?: 0.25;

        // 1. Fetch all schedulable orders (waiting or previously scheduled)
        $orders = $this->db
            ->where_in('status', ['waiting', 'scheduled'])
            ->get('orders')
            ->result();

        if (!$orders) return;

        // 5. Get anchor time: latest end_date across ALL in_progress jobs
        //    (parallel support — multiple orders may be running simultaneously)
        $in_progress = $this->db
            ->where('status', 'in_progress')
            ->order_by('end_date', 'DESC')
            ->limit(1)
            ->get('production_schedule')
            ->row();

        if ($in_progress) {
            $anchor     = $this->force_business_hours($in_progress->end_date);
            $queue      = $in_progress->queue_position + 1;
        } else {
            $anchor = $this->force_business_hours(date('Y-m-d H:i:s'));
            $queue  = 1;
        }

        // 2. Queue-aware urgency classification
        //
        //    Worst-case scenario: this job runs LAST after all other pending jobs.
        //    If even in this worst case, the slack between its projected finish and
        //    its deadline is less than (slack_buffer × its own duration) → urgent.
        //
        //    This prevents SHORT jobs from being incorrectly marked urgent just
        //    because their deadline is soon — they'll still fit before long jobs.
        //
        $total_mins = array_sum(array_column(
            array_map(fn($o) => (array)$o, $orders),
            'est_duration'
        ));

        $urgent = [];
        $normal = [];

        foreach ($orders as $o) {
            // Simulate: all OTHER jobs run first, then this one
            $others_mins = $total_mins - $o->est_duration;
            $worst_start = $this->advance_time($anchor, $others_mins);
            $worst_end   = $this->advance_time($worst_start, $o->est_duration);

            $slack_mins   = (strtotime($o->deadline) - strtotime($worst_end)) / 60;
            $buffer_needed = $o->est_duration * $slack_buffer;

            if ($slack_mins < $buffer_needed) {
                $urgent[] = $o;
            } else {
                $normal[] = $o;
            }
        }

        // 3. Sort each bucket
        //    Urgent → Earliest Deadline First (protect hard deadlines)
        usort($urgent, fn($a, $b) => strtotime($a->deadline) - strtotime($b->deadline));

        //    Normal → Shortest Job First (pure SJF throughput)
        usort($normal, fn($a, $b) => $a->est_duration - $b->est_duration);

        $final = array_merge($urgent, $normal);

        // 4. Delete old unstarted scheduled entries
        $this->db->where('status', 'scheduled')->delete('production_schedule');

        // 6. Generate new schedule sequentially from anchor
        $this->db->trans_start();

        $current_time = $anchor;
        foreach ($final as $o) {
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
     * Logic:
     *  1. Find the current queue tail (latest end_date of any scheduled/in_progress job)
     *  2. Project when the NEW job would finish if added to the end of the queue
     *  3. Add a safety buffer (urgency_slack_buffer × est_duration) on top
     *
     * The result is the earliest date the customer can realistically be promised.
     */
    public function get_earliest_deadline($est_duration_mins)
    {
        $slack_buffer = $this->config->item('urgency_slack_buffer') ?: 0.25;

        // 1. Find the anchor (the end of the currently generated schedule)
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

        // 2. Account for 'waiting' orders that are not yet in production_schedule
        $waiting_orders = $this->db
            ->select_sum('est_duration')
            ->where('status', 'waiting')
            ->get('orders')
            ->row();

        $waiting_mins = $waiting_orders ? (int) $waiting_orders->est_duration : 0;
        
        // Push the anchor past all waiting orders
        $queue_tail = $this->advance_time($anchor, $waiting_mins);

        // 3. Project when THIS new job would finish
        $projected_end = $this->advance_time($queue_tail, $est_duration_mins);

        // 4. Add safety buffer
        $buffer_mins  = (int) ceil($est_duration_mins * $slack_buffer);
        $safe_deadline = $this->advance_time($projected_end, $buffer_mins);

        return [
            'earliest_date'    => date('Y-m-d', strtotime($safe_deadline)),
            'queue_tail'       => $queue_tail,
            'projected_finish' => $projected_end,
            'waiting_mins'     => $waiting_mins
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
