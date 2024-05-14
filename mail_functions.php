<?php
// Load email configuration
function loadMailConfig() {
    $configFile = __DIR__ . '/mail_config.json';
    if (file_exists($configFile)) {
        return json_decode(file_get_contents($configFile), true);
    }
    return [];
}

// Send email using PHP's mail function
function sendMail($to, $subject, $message, $headers = '') {
    $mailConfig = loadMailConfig();
    if (isset($mailConfig['enabled']) && $mailConfig['enabled']) {
        if (!empty($mailConfig['smtp_host'])) {
            // If SMTP configuration is provided, set the necessary headers
            ini_set('SMTP', $mailConfig['smtp_host']);
            ini_set('smtp_port', $mailConfig['smtp_port']);
            ini_set('sendmail_from', $mailConfig['smtp_user']);
            $headers .= 'From: ' . $mailConfig['smtp_user'] . "\r\n";
        }
        return mail($to, $subject, $message, $headers);
    }
    return false;
}