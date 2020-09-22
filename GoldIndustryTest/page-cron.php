<?php
/**
 * Template Name: Cron Page
 */

if( isset($_GET['type']) && $_GET['type'] == 'job_alert' ){
    job_alerts_cron();
}

if( isset($_GET['type']) && $_GET['type'] == 'import_jobs' ) {
    import_jobs_cron_job();
}