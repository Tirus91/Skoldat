<?php

function skp_minutes_service_mailing($time) {
    global $registry;

    $res = dibi::query('SELECT * FROM [:prefix:mail]');
    foreach ($res as $val) {
        $mail = new SkMail($registry);
        if ($mail->send_mail(array(array('mail' => $val['recipient_mail'])), $val['subject_mail'], $val['body_mail'])) {
            dibi::query('DELETE FROM [:prefix:mail] WHERE %and',$val);
        } else {
            dibi::query('UPDATE [:prefix:mail] SET ',array('sending_error_mail'=>$mail->error,'sending_count_mail'=>($val['sending_count_mail']+1)),' WHERE %and',$val);
        }
    }

    //$mail->send_mail(array('mail','name'), $subject = null, $body = null);


    return true;
}
