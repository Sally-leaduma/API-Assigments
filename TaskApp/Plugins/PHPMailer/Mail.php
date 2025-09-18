<?php
require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;



//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'sally.leaduma@strathmore.edu';                     //SMTP username
    $mail->Password   = 'jghgrxqkoazflhvv';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('sally.leaduma@strathmore.edu', 'Sally Leaduma');
    $mail->addAddress('leadumasally@gmail.com', 'Leaduma');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Welcome to the API Class!';
    $mail->Body    = '<h2>Welcome to the API Class!</h2>
    <p>Dear SCES Students,</p>
    <p>We are excited to welcome you all to the API (Application Programming Interfaces) class this semester. 
    This course will be a great opportunity to learn how APIs power modern applications, 
    how to connect different systems, and how to build scalable solutions.</p>
    <p>Over the coming weeks, we’ll explore both theory and hands-on practice — 
    so come ready to code, collaborate, and grow together.</p>
    <p><b>Let’s make this an amazing learning journey!</b></p>
    <p>Best regards,<br>API Class Instructor</p>';
     $mail->AltBody = 'Dear SCES Students, Welcome to the API class! We are excited to begin this journey with you.';


     // Send the email

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}