<?php

class PlannedController extends Controllers_AbstractController {

    function __construct($registry) {
        $this->sk_syst = $registry['sk_syst'];
    }

    function index() {
        $cur_time = new DateTime;
        $this->hourly($cur_time);
        $this->daily($cur_time);
        $this->weekly($cur_time);
        $this->monthly($cur_time);
        $this->yearly($cur_time);
        $this->minutes($cur_time);
    }

    function daily($time = null) {
        if ($time === null) {
            $time = new DateTime();
        }
        if (($time instanceof DateTime) && ($this->sk_syst->cron_daily_syst < $time)) {
            // denní
            $service_file = glob('./services/daily_service/*.php');
            foreach ($service_file as $file_name) {
                if (file_exists($file_name)) {
                    require_once $file_name;
                    $func_name = 'skp' . substr(basename($file_name, '.php'), 3);
                    if (function_exists($func_name)) {
                        $func_name($time);
                    }
                }
            }
            $this->sk_syst->update_cron_time('daily');
        }
    }

    function monthly($time = null) {
        if ($time === null) {
            $time = new DateTime();
        }
        if (($time instanceof DateTime) && ($this->sk_syst->cron_monthly_syst < $time)) {
            //měsíční 
            $service_file = glob('./services/monthly_service/*.php');
            foreach ($service_file as $file_name) {
                if (file_exists($file_name)) {
                    require_once $file_name;
                    $func_name = 'skp' . substr(basename($file_name, '.php'), 3);
                    if (function_exists($func_name)) {
                        $func_name($time);
                    }
                }
            }
            $this->sk_syst->update_cron_time('monthly');
        }
    }

    function hourly($time = null) {
        if ($time === null) {
            $time = new DateTime();
        }
        if (($time instanceof DateTime) && ($this->sk_syst->cron_hourly_syst < $time)) {
            //hodinové
            $service_file = glob('./services/hourly_service/*.php');
            foreach ($service_file as $file_name) {
                if (file_exists($file_name)) {
                    require_once $file_name;
                    $func_name = 'skp' . substr(basename($file_name, '.php'), 3);
                    if (function_exists($func_name)) {
                        $func_name($time);
                    }
                }
            }
            $this->sk_syst->update_cron_time('hourly');
        }
    }

    function weekly($time = null) {
        if ($time === null) {
            $time = new DateTime();
        }
        if (($time instanceof DateTime) && ($this->sk_syst->cron_weekly_syst < $time)) {
            //týdenní
            $service_file = glob('./services/weekly_service/*.php');
            foreach ($service_file as $file_name) {
                if (file_exists($file_name)) {
                    require_once $file_name;
                    $func_name = 'skp' . substr(basename($file_name, '.php'), 3);
                    if (function_exists($func_name)) {
                        $func_name($time);
                    }
                }
            }
            $this->sk_syst->update_cron_time('weekly');
        }
    }

    function minutes($time = null) {
        if ($time === null) {
            $time = new DateTime();
        }
        if (($time instanceof DateTime) && ($this->sk_syst->cron_minutes_syst < $time)) {
            //týdenní
            $service_file = glob('./services/minutes_service/*.php');
            foreach ($service_file as $file_name) {
                if (file_exists($file_name)) {
                    require_once $file_name;
                    $func_name = 'skp' . substr(basename($file_name, '.php'), 3);
                    if (function_exists($func_name)) {
                        $func_name($time);
                    }
                }
            }
            $this->sk_syst->update_cron_time('minutes');
        }
    }

    function yearly($time = null) {
        if ($time === null) {
            $time = new DateTime();
        }
        if (($time instanceof DateTime) && ($this->sk_syst->cron_yearly_syst < $time)) {
            //týdenní
            $service_file = glob('./services/yearly_service/*.php');
            foreach ($service_file as $file_name) {
                if (file_exists($file_name)) {
                    require_once $file_name;
                    $func_name = 'skp' . substr(basename($file_name, '.php'), 3);
                    if (function_exists($func_name)) {
                        $func_name($time);
                    }
                }
            }
            $this->sk_syst->update_cron_time('yearly');
        }
    }

}

?>
