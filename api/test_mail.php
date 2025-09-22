<?php
require 'send_email.php';

$mailer = new SendEmail();
$result = $mailer->sendEmail(
    "ivla.versoza.coc@phinmaed.com",        // 👈 replace with your real email
    "Test Email from PHPMailer",
    "<p>Hello! This is a test email sent via PHPMailer.</p>"
);

var_dump($result); // 👈 will show 1 (success) or "Mailer Error: ..."
