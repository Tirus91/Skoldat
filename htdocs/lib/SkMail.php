<?php

class SkMail {

    protected $mail;
    public $error;
    public $setting;

    function __construct($registry) {
        if (!file_exists('./class/phpmailer/class.phpmailer.php')) {
            die('Something is wrong - #10');
        }
        $this->setting = $registry['sk_syst'];
        require_once './class/phpmailer/class.phpmailer.php';
        $this->mail = new PHPMailer();
        $this->error = null;
        switch ($this->setting->type_send_mail_syst) {
            case 1:
                $this->mail->IsSMTP();
                $this->mail->Host = $this->setting->smtp_server_syst;           // zadáme adresu SMTP serveru
                $this->mail->SMTPAuth = $this->setting->smtp_auth_syst;         // nastavíme true v případě, že server vyžaduje SMTP autentizaci
                $this->mail->Username = $this->setting->smtp_auth_email_syst;   // uživatelské jméno pro SMTP autentizaci
                $this->mail->Password = $this->setting->smtp_auth_pwd_syst;     // heslo pro SMTP autentizaci
                $this->mail->Port = $this->setting->smtp_port_syst;             // Port pro smtp server
                $this->mail->SMTPSecure = $this->setting->smtp_secure_syst;     // Typ spojení (ssl/tsl)
                break;
            case 2:
                $this->mail->IsMail();
                break;
            default:
                $this->mail->IsMail();
                break;
        }
    }

    public function send_mail($to = array(), $subject = null, $body = null, $from = array()) {
        // adresa odesílatele skriptu
        
        if (isset($from['from'])) {
            $this->mail->From = $from['from'];
        } else {
            $this->mail->From = $this->setting->smtp_auth_email_syst;
        }

        // jméno odesílatele skriptu (zobrazí se vedle adresy odesílatele)
        if (isset($from['fromname'])) {
            $this->mail->FromName = $from['fromname'];
        } else {
            $this->mail->FromName = $this->setting->smtp_auth_email_syst;
        }

        foreach ($to as $recipient) {
            if (isset($recipient['name'])) {
                $this->mail->AddAddress($recipient['mail'], $recipient['name']);
            } else {
                
                $this->mail->AddAddress($recipient['mail']);
            }
        }
        if ($subject != '') {
            $this->mail->Subject = $subject;
        } else {
            $body = '';
        }

        if ($body != '') {
            switch (strtolower($this->setting->mail_format_syst)) {
                case 'html':
                    break;
                case 'txt':
                    $body = nl2br($body);
                    break;
                default:
                    $body = nl2br($body);
                    break;
            }
            $this->mail->Body = $body;                                          // nastavíme tělo e-mailu
            $this->mail->WordWrap = $this->setting->mail_wordwrap_syst;         // nastavení zalamování řádků po určitém počtu znaků
            $this->mail->CharSet = "utf-8";                                     // nastavíme kódování, ve kterém odesíláme e-mail
            if (!$this->mail->Send()) {
                $this->error .= 'Something is wrong - #11' . $this->mail->ErrorInfo;
                return false;
            } else {
                return true;
            }
        }
        $this->error .= 'Body is empty';
        return false;
    }

}