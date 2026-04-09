<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('format_duration')) {
    /**
     * Formats minutes into days, hours, and minutes.
     * Based on 510 minutes per operational day (08:30 - 17:00).
     */
    function format_duration($mins) {
        if ($mins <= 0) return "0 mins";
        $d = floor($mins / 510);
        $rem = $mins % 510;
        $h = floor($rem / 60);
        $m = $rem % 60;
        $parts = [];
        if ($d > 0) $parts[] = $d . "d";
        if ($h > 0) $parts[] = $h . "h";
        if ($m > 0) $parts[] = $m . "m";
        return implode(" ", $parts);
    }
}

if (!function_exists('get_operational_minutes')) {
    /**
     * Calculates operational minutes between two date strings,
     * skipping Sundays and respecting 08:30 - 17:00 business hours.
     */
    function get_operational_minutes($start_txt, $end_txt) {
        $start = new DateTime($start_txt);
        $end = new DateTime($end_txt);
        if ($start >= $end) return 0;
        
        $minutes = 0;
        while ($start < $end) {
            if ($start->format('w') == 0) { // skip sunday
                $start->modify('+1 day')->setTime(8, 30, 0);
                continue;
            }
            $curr_mins = (int)$start->format('G') * 60 + (int)$start->format('i');
            
            // cap start bounds
            if ($curr_mins < 510) { 
                $start->setTime(8, 30, 0); 
                $curr_mins = 510; 
            }
            if ($curr_mins >= 1020) { 
                $start->modify('+1 day')->setTime(8, 30, 0); 
                continue; 
            }
            
            if ($start->format('Y-m-d') == $end->format('Y-m-d')) {
                $eh = (int)$end->format('G');
                $em = (int)$end->format('i');
                $end_mins = $eh * 60 + $em;
                if ($end_mins > 1020) $end_mins = 1020;
                $minutes += max(0, $end_mins - $curr_mins);
                break;
            } else {
                $minutes += max(0, 1020 - $curr_mins);
                $start->modify('+1 day')->setTime(8, 30, 0);
            }
        }
        return $minutes;
    }
}

if (!function_exists('render_status_badge')) {
    /**
     * Renders an HTML badge for order status.
     */
    function render_status_badge($status) {
        $badge_class = 'badge-pending';
        $badge_text = ucfirst($status);

        if ($status == 'waiting') {
            $badge_class = 'badge-pending';
            $badge_text = 'Waiting';
        } elseif ($status == 'scheduled') {
            $badge_class = 'badge-confirmed';
            $badge_text = 'Scheduled';
        } elseif ($status == 'in_progress') {
            $badge_class = 'badge-production';
            $badge_text = 'In Production';
        } elseif ($status == 'done') {
            $badge_class = 'badge-done';
            $badge_text = 'Done';
        }

        return '<span class="badge ' . $badge_class . '">' . $badge_text . '</span>';
    }
}

if (!function_exists('render_design_link')) {
    /**
     * Renders a download link for design files.
     */
    function render_design_link($filename) {
        if (!$filename) {
            return '<span style="color: var(--smoke);">No file</span>';
        }
        $file_url = base_url() . 'gambar/orders/' . $filename;
        return '<a href="' . $file_url . '" target="_blank" style="color:#60a5fa; text-decoration:underline; cursor:pointer;" download>' . htmlspecialchars($filename) . '</a>';
    }
}

if (!function_exists('format_order_date')) {
    /**
     * Standardized short date format for orders.
     */
    function format_order_date($date) {
        return $date ? date('j M', strtotime($date)) : '<span style="color:var(--smoke)">TBD</span>';
    }
}

if (!function_exists('get_remaining_today_minutes')) {
    /**
     * Returns the number of operational minutes remaining in today's business hours.
     * Business hours: 08:30 – 17:00. Returns 0 on Sundays or after closing time.
     * Used to check if a quick-insert job can still be squeezed into today.
     */
    function get_remaining_today_minutes() {
        $now = new DateTime();
        if ($now->format('w') == 0) return 0; // Sunday — no business

        $now_mins   = (int)$now->format('G') * 60 + (int)$now->format('i');
        $start_mins = 8 * 60 + 30;  // 08:30 = 510
        $end_mins   = 17 * 60;      // 17:00 = 1020

        if ($now_mins >= $end_mins)   return 0;                      // past closing
        if ($now_mins < $start_mins)  return $end_mins - $start_mins; // before opening — full day available

        return $end_mins - $now_mins;
    }
}
