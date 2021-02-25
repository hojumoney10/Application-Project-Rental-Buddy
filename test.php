<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'rentalproject.fanshawe@gmail.com';
$mail->Password = ')(4u]B8kY$-0v;[1agkF';
$mail->setFrom('rentalproject.fanshawe@gmail.com', 'Rental Buddy');
$mail->addReplyTo('rentalproject.fanshawe@gmail.com', 'Rental Buddy');
$mail->addAddress('taehyungkim@outlook.com', 'Taehyung Kim');
$mail->Subject = 'Testing PHPMailer sh file test';
$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->Body = 'This is a plain text message body';
//$mail->addAttachment('test.txt');
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'The email message was sent.';
}
?>
