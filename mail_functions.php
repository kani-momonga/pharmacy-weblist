<?php

function sendMail($to, $subject, $message, $additionalHeaders = '') {
    $mailConfig = json_decode(file_get_contents(__DIR__ . '/mail_config.json'), true);

    if (!$mailConfig['enabled']) {
        return;
    }

    $headers = "From: " . $mailConfig['from_email'] . "\r\n" . $additionalHeaders;

    if ($mailConfig['enabled']) {
        if (isset($mailConfig['smtp_host']) && $mailConfig['smtp_host']) {
            ini_set('SMTP', $mailConfig['smtp_host']);
            ini_set('smtp_port', $mailConfig['smtp_port']);
            ini_set('sendmail_from', $mailConfig['from_email']);

            $username = $mailConfig['smtp_user'];
            $password = $mailConfig['smtp_pass'];

            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";

            $headers .= 'From: ' . $mailConfig['from_email'] . "\r\n" .
                        'Reply-To: ' . $mailConfig['from_email'] . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

            $transport = (new Swift_SmtpTransport($mailConfig['smtp_host'], $mailConfig['smtp_port']))
                ->setUsername($username)
                ->setPassword($password);

            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message($subject))
                ->setFrom([$mailConfig['from_email'] => 'Pharmacy Management System'])
                ->setTo([$to])
                ->setBody($message, 'text/html');

            $result = $mailer->send($message);

            return $result;
        } else {
            mail($to, $subject, $message, $headers);
        }
    }
}
?>
