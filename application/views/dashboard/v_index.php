<?php
// ─── Helpers ───────────────────────────────────────────────────────────────
function fmt_mins($m) {
    if ($m < 60)  return $m . ' min';
    $h = floor($m / 60); $r = $m % 60;
    return $r > 0 ? "{$h}h {$r}m" : "{$h}h";
}
function fmt_time($dt) {
    return $dt ? date('H:i', strtotime($dt)) : '—';
}
function days_until($deadline) {
    if (!$deadline) return null;
    $diff = (strtotime($deadline) - strtotime(date('Y-m-d'))) / 86400;
    return (int)$diff;
}
?>

<!-- ═══════════════════════════════════════════════ -->
<!-- PAGE: Admin Dashboard (Live Data)              -->
<!-- ═══════════════════════════════════════════════ -->
<div class="page active" id="page-admin-dashboard">

    <!-- ── SECTION 1: KPI STAT CARDS ─────────────────── -->
    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:14px; margin-bottom:20px;">

        <div class="stat-card">
            <div class="stat-val"><?php echo $stats->total_orders; ?></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-sub" style="color:var(--smoke);">
                <?php echo $stats->waiting; ?> waiting · <?php echo $stats->scheduled; ?> scheduled
            </div>
        </div>

        <div class="stat-card" style="--before-color: #60a5fa;">
            <div class="stat-val"><?php echo $stats->new_today; ?></div>
            <div class="stat-label">New Today</div>
            <div class="stat-sub" style="color:#60a5fa;">
                <?php echo date('d M Y'); ?>
            </div>
        </div>

        <div class="stat-card" style="--before-color: #4ade80;">
            <div class="stat-val"><?php echo $stats->in_progress; ?></div>
            <div class="stat-label">In Production</div>
            <div class="stat-sub" style="color:#4ade80;">
                <?php echo $stats->in_progress > 0 ? '● Running now' : 'Nothing running'; ?>
            </div>
        </div>

        <div class="stat-card" style="--before-color: #c084fc;">
            <div class="stat-val"><?php echo $stats->done_month; ?></div>
            <div class="stat-label">Done This Month</div>
            <div class="stat-sub" style="color:#c084fc;">
                <?php echo date('F Y'); ?>
            </div>
        </div>

    </div>

    <!-- ── SECTION 2: QUICK ACTION SHORTCUTS ─────────── -->
    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:12px; margin-bottom:20px;">

        <a href="<?php echo base_url() . 'dashboard/new_order'; ?>" id="shortcut-new-order"
           style="display:flex; align-items:center; gap:12px; background:var(--panel); border:1px solid var(--border); padding:14px 16px; text-decoration:none; transition:border-color .2s, background .2s;"
           onmouseover="this.style.borderColor='var(--ember)'; this.style.background='rgba(232,160,32,0.07)'"
           onmouseout="this.style.borderColor='var(--border)'; this.style.background='var(--panel)'">
            <div style="width:36px; height:36px; background:var(--ember); display:flex; align-items:center; justify-content:center; font-size:20px; color:var(--ink); flex-shrink:0;">＋</div>
            <div>
                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--cream); letter-spacing:0.06em;">NEW ORDER</div>
                <div style="font-size:10px; color:var(--smoke); margin-top:2px;">Create & schedule</div>
            </div>
        </a>

        <a href="<?php echo base_url() . 'dashboard/orders'; ?>" id="shortcut-orders"
           style="display:flex; align-items:center; gap:12px; background:var(--panel); border:1px solid var(--border); padding:14px 16px; text-decoration:none; transition:border-color .2s, background .2s;"
           onmouseover="this.style.borderColor='#60a5fa'; this.style.background='rgba(96,165,250,0.07)'"
           onmouseout="this.style.borderColor='var(--border)'; this.style.background='var(--panel)'">
            <div style="width:36px; height:36px; background:#1e3a5f; border:1px solid #60a5fa; display:flex; align-items:center; justify-content:center; font-size:18px; color:#60a5fa; flex-shrink:0;">≡</div>
            <div>
                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--cream); letter-spacing:0.06em;">ALL ORDERS</div>
                <div style="font-size:10px; color:var(--smoke); margin-top:2px;">View & manage</div>
            </div>
        </a>

        <a href="<?php echo base_url() . 'dashboard/schedule'; ?>" id="shortcut-schedule"
           style="display:flex; align-items:center; gap:12px; background:var(--panel); border:1px solid var(--border); padding:14px 16px; text-decoration:none; transition:border-color .2s, background .2s;"
           onmouseover="this.style.borderColor='#4ade80'; this.style.background='rgba(74,222,128,0.07)'"
           onmouseout="this.style.borderColor='var(--border)'; this.style.background='var(--panel)'">
            <div style="width:36px; height:36px; background:#0d3321; border:1px solid #4ade80; display:flex; align-items:center; justify-content:center; font-size:18px; color:#4ade80; flex-shrink:0;">◫</div>
            <div>
                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--cream); letter-spacing:0.06em;">SCHEDULE</div>
                <div style="font-size:10px; color:var(--smoke); margin-top:2px;">Gantt · SJF queue</div>
            </div>
        </a>

        <a href="<?php echo base_url() . 'dashboard/customers'; ?>" id="shortcut-customers"
           style="display:flex; align-items:center; gap:12px; background:var(--panel); border:1px solid var(--border); padding:14px 16px; text-decoration:none; transition:border-color .2s, background .2s;"
           onmouseover="this.style.borderColor='#c084fc'; this.style.background='rgba(192,132,252,0.07)'"
           onmouseout="this.style.borderColor='var(--border)'; this.style.background='var(--panel)'">
            <div style="width:36px; height:36px; background:#2a1545; border:1px solid #c084fc; display:flex; align-items:center; justify-content:center; font-size:18px; color:#c084fc; flex-shrink:0;">◎</div>
            <div>
                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--cream); letter-spacing:0.06em;">CUSTOMERS</div>
                <div style="font-size:10px; color:var(--smoke); margin-top:2px;">Browse · add new</div>
            </div>
        </a>

    </div>

    <!-- ── SECTION 3 + 4: TWO-COLUMN LAYOUT ───────────── -->
    <div style="display:grid; grid-template-columns: 1fr 360px; gap:16px;">

        <!-- ══ LEFT: TODAY'S WORK ORDER TO-DO LIST ══════ -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Today's Work Orders</div>
                <span style="font-size:9px; color:var(--smoke); letter-spacing:0.1em; text-transform:uppercase;">
                    <?php echo date('l, d F Y'); ?>
                </span>
            </div>

            <?php if (empty($today_work)): ?>
                <div style="padding:32px; text-align:center; color:var(--smoke);">
                    <div style="font-size:28px; margin-bottom:8px;">✓</div>
                    <div style="font-family:'Syne',sans-serif; font-size:13px;">No production scheduled today</div>
                    <div style="font-size:11px; margin-top:4px;">Queue is clear for today</div>
                </div>
            <?php else: ?>
                <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
                    <?php foreach ($today_work as $job):
                        $is_active   = $job->schedule_status === 'in_progress';
                        $is_done     = $job->order_status   === 'done';
                        $accent      = $is_done ? '#6b7280' : ($is_active ? '#4ade80' : '#e8a020');
                        $label_txt   = $is_done ? 'Done' : ($is_active ? '● In Production' : '◌ Scheduled');
                        $days_left   = days_until($job->deadline);
                        $dl_color    = ($days_left !== null && $days_left <= 0) ? '#f87171' : (($days_left !== null && $days_left <= 1) ? '#e8a020' : '#6b7280');
                        $dl_label    = ($days_left !== null) ? ($days_left < 0 ? abs($days_left) . 'd overdue' : ($days_left === 0 ? 'Due today' : $days_left . 'd left')) : '';

                        // Progress bar for in_progress
                        $progress = 0;
                        if ($is_active && $job->start_date && $job->end_date) {
                            $now_ts   = time();
                            $start_ts = strtotime($job->start_date);
                            $end_ts   = strtotime($job->end_date);
                            $total    = $end_ts - $start_ts;
                            $elapsed  = $now_ts - $start_ts;
                            $progress = $total > 0 ? min(100, max(0, round($elapsed / $total * 100))) : 0;
                        }
                    ?>
                    <div id="workorder-<?php echo $job->order_id; ?>"
                         style="background:var(--rail); border:1px solid <?php echo $is_active ? 'rgba(74,222,128,0.3)' : 'var(--border)'; ?>; border-left:3px solid <?php echo $accent; ?>; padding:12px 14px; position:relative; opacity:<?php echo $is_done ? '0.55' : '1'; ?>; transition:opacity .3s;">

                        <!-- Row 1: Queue + Status + Time -->
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                            <span class="queue-num" style="background:<?php echo $accent; ?>; color:var(--ink); min-width:22px; text-align:center;"><?php echo $job->queue_position; ?></span>
                            <span style="font-size:10px; color:<?php echo $accent; ?>; text-transform:uppercase; letter-spacing:0.12em; font-weight:600;"><?php echo $label_txt; ?></span>
                            <span style="margin-left:auto; font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--smoke); background:var(--panel); padding:2px 8px; border:1px solid var(--border);">
                                <?php echo fmt_time($job->start_date); ?> → <?php echo fmt_time($job->end_date); ?>
                            </span>
                        </div>

                        <!-- Row 2: Customer + Product + Qty -->
                        <div style="display:flex; align-items:baseline; gap:8px; margin-bottom:6px;">
                            <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px; color:var(--cream);"><?php echo htmlspecialchars($job->customer_name); ?></div>
                            <div style="font-size:10px; color:var(--smoke);">·</div>
                            <div style="font-size:11px; color:var(--smoke);"><?php echo htmlspecialchars($job->product_type ?: '—'); ?></div>
                            <div style="font-size:11px; color:var(--smoke);"><?php echo $job->qty; ?> pcs</div>
                            <div style="font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--smoke); margin-left:auto;"><?php echo htmlspecialchars($job->order_code); ?></div>
                        </div>

                        <!-- Row 3: Duration + Deadline + Mark Done button -->
                        <div style="display:flex; align-items:center; gap:8px; margin-top:4px;">
                            <span style="font-size:10px; color:var(--smoke);"><?php echo fmt_mins($job->est_duration); ?></span>
                            <?php if ($job->deadline): ?>
                            <span style="font-size:10px; color:<?php echo $dl_color; ?>; padding:1px 6px; border:1px solid <?php echo $dl_color; ?>; border-radius:2px;">
                                <?php echo date('d M', strtotime($job->deadline)); ?><?php echo $dl_label ? ' · ' . $dl_label : ''; ?>
                            </span>
                            <?php endif; ?>

                            <?php if (!$is_done): ?>
                            <a href="<?php echo base_url() . 'dashboard/update_status/' . $job->order_id . '/done'; ?>"
                               id="done-btn-<?php echo $job->order_id; ?>"
                               onclick="return confirm('Mark <?php echo htmlspecialchars($job->order_code); ?> as Done?')"
                               style="margin-left:auto; font-size:10px; font-family:'Syne',sans-serif; font-weight:700; letter-spacing:0.08em; padding:4px 12px; background:transparent; border:1px solid #4ade80; color:#4ade80; text-decoration:none; text-transform:uppercase; cursor:pointer; transition:background .2s, color .2s;"
                               onmouseover="this.style.background='#4ade80'; this.style.color='var(--ink)'"
                               onmouseout="this.style.background='transparent'; this.style.color='#4ade80'">
                                ✓ Done
                            </a>
                            <?php else: ?>
                            <span style="margin-left:auto; font-size:10px; font-family:'Syne',sans-serif; color:#4ade80; letter-spacing:0.08em;">✓ COMPLETED</span>
                            <?php endif; ?>
                        </div>

                        <!-- Progress bar (only for in_progress) -->
                        <?php if ($is_active): ?>
                        <div class="prog-track" style="margin-top:8px;">
                            <div class="prog-fill" style="width:<?php echo $progress; ?>%; background:#4ade80; transition:width 1s;"></div>
                        </div>
                        <div style="font-size:9px; color:var(--smoke); margin-top:3px; text-align:right;"><?php echo $progress; ?>% elapsed</div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ══ RIGHT COLUMN ═══════════════════════════════ -->
        <div style="display:flex; flex-direction:column; gap:14px;">

            <!-- ⚠ DEADLINE ALERTS ─────────────────────── -->
            <?php if (!empty($deadline_alerts)): ?>
            <div class="panel" style="border-left:3px solid #f87171;">
                <div class="panel-header">
                    <div class="panel-title" style="color:#f87171;">⚠ Deadline Alerts</div>
                    <span style="font-size:9px; color:#f87171; letter-spacing:0.1em;">DUE ≤ 1 DAY</span>
                </div>
                <div style="padding:10px 14px; display:flex; flex-direction:column; gap:8px;">
                    <?php foreach ($deadline_alerts as $alert):
                        $d = days_until($alert->deadline);
                        $d_txt = $d < 0 ? abs($d) . 'd overdue!' : ($d === 0 ? 'Due TODAY' : 'Due tomorrow');
                        $d_col = $d <= 0 ? '#f87171' : '#e8a020';
                    ?>
                    <div style="display:flex; align-items:center; gap:10px; padding:8px 10px; background:rgba(248,113,113,0.07); border:1px solid rgba(248,113,113,0.25);">
                        <div style="flex:1; min-width:0;">
                            <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:11px; color:var(--cream); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?php echo htmlspecialchars($alert->customer_name); ?></div>
                            <div style="font-family:'JetBrains Mono',monospace; font-size:9px; color:var(--smoke);"><?php echo htmlspecialchars($alert->order_code); ?> · <?php echo $alert->qty; ?> pcs</div>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <div style="font-size:10px; color:<?php echo $d_col; ?>; font-weight:700;"><?php echo $d_txt; ?></div>
                            <div style="font-size:9px; color:var(--smoke);"><?php echo date('d M', strtotime($alert->deadline)); ?></div>
                        </div>
                        <a href="<?php echo base_url() . 'dashboard/orders'; ?>"
                           style="font-size:9px; color:#f87171; text-decoration:none; border:1px solid #f87171; padding:2px 6px; font-family:'Syne',sans-serif; font-weight:700; letter-spacing:0.06em; transition:background .2s;"
                           onmouseover="this.style.background='rgba(248,113,113,0.2)'"
                           onmouseout="this.style.background='transparent'">VIEW</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 📋 LIVE QUEUE PREVIEW ──────────────────── -->
            <div class="panel" style="flex:1;">
                <div class="panel-header">
                    <div class="panel-title">Queue Preview</div>
                    <span style="font-size:9px; color:var(--smoke); letter-spacing:0.1em; text-transform:uppercase;">TOP 5 · SJF</span>
                </div>
                <?php if (empty($queue_preview)): ?>
                    <div style="padding:24px; text-align:center; color:var(--smoke); font-size:11px;">Queue is empty</div>
                <?php else: ?>
                <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
                    <?php foreach ($queue_preview as $item):
                        $q_active  = $item->status === 'in_progress';
                        $q_accent  = $q_active ? '#4ade80' : '#60a5fa';
                        $q_label   = $q_active ? '● In Production' : '◌ Scheduled';
                        $dl_left   = days_until($item->deadline ?? null);
                    ?>
                    <div class="queue-card <?php echo $q_active ? 'in-prod' : ''; ?>">
                        <span class="queue-num"><?php echo $item->queue_position; ?></span>
                        <div style="font-size:10px; color:<?php echo $q_accent; ?>; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:4px;"><?php echo $q_label; ?></div>
                        <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px; color:var(--cream);"><?php echo htmlspecialchars($item->customer_name); ?></div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:3px;">
                            <div style="font-size:11px; color:var(--smoke);"><?php echo $item->qty; ?> pcs · <?php echo fmt_mins($item->est_duration); ?></div>
                            <?php if ($item->deadline && $dl_left !== null && $dl_left <= 2): ?>
                            <div style="font-size:9px; color:<?php echo $dl_left <= 0 ? '#f87171' : '#e8a020'; ?>; padding:1px 5px; border:1px solid <?php echo $dl_left <= 0 ? '#f87171' : '#e8a020'; ?>;">
                                <?php echo $dl_left <= 0 ? 'OVERDUE' : $dl_left . 'd'; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($q_active): ?>
                        <div class="prog-track" style="margin-top:6px;">
                            <?php
                                $pct = 0;
                                if ($item->start_date && $item->end_date) {
                                    $ts = time(); $s = strtotime($item->start_date); $e = strtotime($item->end_date);
                                    $pct = $e > $s ? min(100, max(0, round(($ts-$s)/($e-$s)*100))) : 0;
                                }
                            ?>
                            <div class="prog-fill" style="width:<?php echo $pct; ?>%; background:#4ade80;"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>

                    <?php if (count($queue_preview) >= 5): ?>
                    <a href="<?php echo base_url() . 'dashboard/schedule'; ?>"
                       style="display:block; text-align:center; font-size:10px; color:var(--smoke); text-decoration:none; padding:6px; border:1px solid var(--border); letter-spacing:0.1em; font-family:'Syne',sans-serif; font-weight:600; text-transform:uppercase; transition:color .2s, border-color .2s;"
                       onmouseover="this.style.color='var(--ember)'; this.style.borderColor='var(--ember)'"
                       onmouseout="this.style.color='var(--smoke)'; this.style.borderColor='var(--border)'">
                        View Full Schedule →
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

        </div><!-- /right column -->
    </div><!-- /two-column -->

</div><!-- /page-admin-dashboard -->
