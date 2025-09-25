<?php
// Test script for OTP email functionality
include "send_email.php";

// Test email configuration
$testEmail = "test@example.com"; // Replace with your test email
$testSubject = "Test OTP Email - Demiren Hotel";
$testBody = '
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; background-color: #f9f9f9; padding: 20px; }
        .container { background-color: #fff; border-radius: 10px; padding: 30px; max-width: 600px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #1a73e8; text-align: center; }
        .otp-code { font-size: 32px; font-weight: bold; color: #1a73e8; background: #f0f8ff; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; letter-spacing: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Test OTP Email</h2>
    <p>This is a test email to verify email functionality.</p>
    <div class="otp-code">123456</div>
    <p>If you receive this email, the email system is working correctly.</p>
</div>
</body>
</html>';

echo "Testing email functionality...\n";
echo "Sending test email to: " . $testEmail . "\n";

$sendEmail = new SendEmail();
$result = $sendEmail->sendEmail($testEmail, $testSubject, $testBody);

if ($result === true) {
    echo "✅ Email sent successfully!\n";
    echo "Check your email inbox and spam folder.\n";
} else {
    echo "❌ Email sending failed!\n";
    echo "Check the error logs for more details.\n";
}

echo "\nDebug Information:\n";
echo "SMTP Host: smtp.gmail.com\n";
echo "SMTP Port: 465\n";
echo "SMTP Security: TLS\n";
echo "From: baldozarazieljade96@gmail.com\n";
echo "To: " . $testEmail . "\n";
?>
