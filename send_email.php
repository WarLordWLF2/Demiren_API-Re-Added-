<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    function sendEmail($emailToSent, $emailSubject, $emailBody)
    {
        // Load Composer's autoloader
        require __DIR__ . '/vendor/autoload.php';

        // Local file logger for detailed diagnostics
        $logFile = __DIR__ . '/email_debug.log';
        $logger = function ($message) use ($logFile) {
            $line = '[' . date('c') . '] ' . $message . PHP_EOL;
            @file_put_contents($logFile, $line, FILE_APPEND);
        };

        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings
            // Increase SMTP debug and route it to error_log and local file for diagnostics
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                 // Detailed server-level debug
            $mail->Debugoutput = function ($str, $level) use ($logger) {
                error_log("SMTP Debug (level {$level}): " . $str);
                $logger("SMTP Debug (level {$level}): " . $str);
            };
            $mail->isSMTP();                                       // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                  // SMTP server
            $mail->SMTPAuth   = true;                              // Enable SMTP authentication
            $mail->Username   = 'ikversoza@gmail.com';             // SMTP username
            $mail->Password   = 'izpfukocrjngaogg';                // SMTP password (App Password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
            $mail->Port       = 587;                               // TCP port to connect to (STARTTLS)
            $mail->Timeout    = 30;                                // Timeout in seconds
            $mail->CharSet    = 'UTF-8';                           // Character encoding

            // Recipients
            $mail->setFrom('ikversoza@gmail.com', 'Demiren Hotel');
            $mail->addAddress($emailToSent, 'Guest');              // Add a recipient

            // Content
            $mail->isHTML(true);                                   // HTML email
            $mail->Subject = $emailSubject;
            $mail->Body    = $emailBody;
            $mail->AltBody = 'This is the plain text version of the email.';

            $logger('Attempting to send email to: ' . $emailToSent . ' | Subject: ' . $emailSubject);
            error_log('Attempting to send email to: ' . $emailToSent . ' | Subject: ' . $emailSubject);
            $mail->send();
            $logger('Email sent successfully to: ' . $emailToSent);
            error_log('Email sent successfully to: ' . $emailToSent);
            return true; // Success
        } catch (Exception $e) {
            // Log detailed error information
            $logger('Email sending failed to: ' . $emailToSent);
            error_log('Email sending failed to: ' . $emailToSent);
            if (isset($mail) && method_exists($mail, 'ErrorInfo')) {
                $logger('PHPMailer Error: ' . $mail->ErrorInfo);
                error_log('PHPMailer Error: ' . $mail->ErrorInfo);
            }
            $logger('Exception: ' . $e->getMessage());
            error_log('Exception: ' . $e->getMessage());
            return false; // Failure
        }
    }
}