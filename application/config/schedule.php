<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| SJF Scheduling Configuration
|--------------------------------------------------------------------------
|
| urgency_slack_buffer
|   A job is classified as "urgent" when, even in the absolute worst case
|   (it runs LAST after every other pending job), the remaining slack
|   between its projected finish and its deadline is less than this fraction
|   of its own duration.
|
|   Example: 0.25 = if less than 25% of the job's own duration remains
|   as buffer after worst-case finish, it's bumped to the urgent bucket.
|
|   Lower value  = more jobs become urgent (aggressive)
|   Higher value = fewer jobs urgent, more pure SJF (relaxed)
|   Recommended range: 0.10 – 0.50
|
*/
$config['urgency_slack_buffer'] = 0.25;

/*
|--------------------------------------------------------------------------
| Quick-Insert (Pause-Slot) Configuration
|--------------------------------------------------------------------------
|
| quick_insert_threshold (minutes)
|   The maximum estimated duration for a job to qualify as a "quick insert".
|   Quick jobs jump to the front of the queue (after urgent jobs), allowing
|   them to be slotted into today's remaining capacity without waiting behind
|   the large normal-SJF backlog.
|
|   Recommended: 240 (half a workday = 4 hours)
|   - Below this → order is small enough to be a pause-slot candidate
|   - Above this → treated as a normal SJF job
|   Adjust lower (e.g. 120) for stricter quick-insert, higher for more lenient.
|
| quick_insert_deadline_days
|   The maximum number of days from TODAY that a deadline must be for a job
|   to qualify as a quick insert. This prevents far-future small jobs from
|   skipping the queue unnecessarily.
|
|   Recommended: 2 (must be due within 2 days to qualify)
|
*/
$config['quick_insert_threshold']     = 240;  // minutes — adjust here
$config['quick_insert_deadline_days'] = 2;    // days    — adjust here
