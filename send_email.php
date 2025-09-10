<?php
// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    function sendEmail($emailToSend, $emailSubject, $emailBody)
    {
        // Load Composer's autoloader
        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            // 👉 Turn off debug in production
            $mail->SMTPDebug = 0; 
            $mail->Debugoutput = function ($str, $level) {
                error_log("PHPMailer [$level]: $str"); // logs if needed
            };

            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ikversoza@gmail.com';     // Gmail account
            $mail->Password   = 'izpfukocrjngaogg';        // App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // From / To
            $mail->setFrom('ikversoza@gmail.com', 'Demiren Hotel System');
            $mail->addAddress($emailToSend);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $emailSubject;
            $mail->Body    = $emailBody;
            $mail->AltBody = strip_tags($emailBody);

            $mail->send();
            return 1; // Success
        } catch (Exception $e) {
            return "Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
