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
