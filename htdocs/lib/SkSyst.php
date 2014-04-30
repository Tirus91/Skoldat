<?php

class SkSyst {

    public $type_send_mail_syst;
    public $mail_format_syst;
    public $smtp_server_syst;
    public $smtp_auth_syst;
    public $smtp_auth_email_syst;
    public $smtp_auth_pwd_syst;
    public $smtp_port_syst;
    public $smtp_secure_syst;
    public $mail_sender_name_syst;
    public $mail_wordwrap_syst;
    public $row_show_syst;
    public $site_name_syst;
    public $school_name_syst;
    public $school_address_syst;
    public $school_contact_user_syst;
    public $cron_hourly_syst;
    public $cron_daily_syst;
    public $cron_weekly_syst;
    public $cron_monthly_syst;
    public $cron_yearly_syst;
    public $cron_minutes_syst;
    public $available_theme;

    function __construct() {
        $this->get_default_settings();
        if ($this->check_default_settings() == false) {
            die('Nepodařilo se načíst základní nastavení');
        }
        
        foreach(glob(APPROOT.DS.'templates'.DS.'*',GLOB_ONLYDIR) as $path){
            $this->available_theme[basename($path)]['theme_folder']=basename($path);
            $this->available_theme[basename($path)]['sel']='';
        }
    }

    public function get_default_settings() {
        $row = (array) dibi::fetch('SELECT * FROM [:prefix:syst]');
        foreach ($row as $key => $value) {
            if (substr($key, 0, 4) == 'cron' && $value != '') {
                $this->{$key} = new DateTime($value);
            } else {
                $this->{$key} = $value;
            }
        }
    }

    private function check_default_settings() {
        if ($this->row_show_syst == '') {
            $this->row_show_syst == 20;
        }
        if ($this->type_send_mail_syst == 2 && ($this->smtp_auth_email_syst == '' || $this->smtp_auth_syst == '')) {
            return false;
        }
        if ($this->smtp_auth_syst == 1) {
            $this->smtp_auth_syst = true;
        } else {
            $this->smtp_auth_syst = false;
        }
        return true;
    }

    public function update_default_settings($param) {
        $allowed = array('type_send_mail_syst', 'mail_format_syst', 'smtp_server_syst', 'smtp_auth_syst', 'smtp_auth_email_syst', 'smtp_auth_pwd_syst', 'smtp_port_syst', 'smtp_secure_syst', 'mail_sender_name_syst', 'mail_wordwrap_syst', 'row_show_syst', 'site_name_syst', 'school_name_syst', 'school_address_syst', 'school_contact_user_syst');
        $update = array();
        foreach ($param as $key => $val) {
            if (in_array($key, $allowed)) {
                if ($this->{$key} != $val) {
                    $update[$key] = $val;
                }
            }
        }
        if (sizeof($update) > 0) {
            if (((bool) dibi::query('UPDATE [:prefix:syst] SET ', $update)) == true) {
                $this->get_default_settings();
            }
        }
        return false;
    }

    public function update_cron_time($type = null) {
        $set = array();
        switch ($type) {
            case 'hourly':
                $set['cron_hourly_syst'] = $this->cron_hourly_syst->modify('+1 hours');
                break;
            case 'daily':
                $set['cron_daily_syst'] = $this->cron_daily_syst->modify('+1 days');
                break;
            case 'monthly':
                $set['cron_monthly_syst'] = $this->cron_monthly_syst->modify('+1 months');
                break;
            case 'weekly':
                $set['cron_weekly_syst'] = $this->cron_weekly_syst->modify('+7 days');
                break;
            case 'yearly':
                $set['cron_yearly_syst'] = $this->cron_yearly_syst->modify('+1 years');
                break;
            case 'minutes':
                $set['cron_minutes_syst'] = $this->cron_minutes_syst->modify('+5 minutes');
                break;
        }
        dibi::query('UPDATE [:prefix:syst] SET ', $set);
    }

    function __destruct() {
        
    }

}
