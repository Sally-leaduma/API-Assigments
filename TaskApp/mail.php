<?php
require __DIR__ . '/Plugins/PHPMailer/src/Exception.php';
require __DIR__ . '/Plugins/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/Plugins/PHPMailer/src/SMTP.php';
require_once __DIR__. '/list.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$age   = $_POST['age']   ?? '';

if (empty($name) || empty($email) || empty($phone) || empty($age)) {
    echo "❌ Name, email, phone, and age are required.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Invalid email format: $email";
    exit;
}
$check = $conn->prepare("SELECT id FROM contestants WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "❌ This email ($email) is already registered for the contest.";
    exit;
}
$check->close();
//  Insert into DB
$stmt = $conn->prepare("INSERT INTO contestants(name, email, phone, age) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $age);
$stmt->execute();
$stmt->close();



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
    $mail->setFrom('sally.leaduma@strathmore.edu', 'Fally FashionHub');
    $mail->addAddress($email, $name);     //Add a recipient

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = "Welcome to the Fally FashionHub Contest, $name!";

    $mail->Body    = "
    <h2>Hello $name,</h2>
        <p>🎉 Thank you for joining the <b>Fally FashionHub Contest</b>!</p>
        <p><b>Your Details:</b><br>
        📧 Email: $email<br>
        📱 Phone: $phone<br>
        🎂 Age: $age</p>
        <p>We are excited to have you on board. Stay tuned for updates, rules, and announcements.</p>
        <p><b>Good luck, and let’s make this contest unforgettable! 🌟</b></p>
        <p>Best regards,<br>Fally FashionHub Team</p>
    ";
    $mail->AltBody = "Hello $name,\n\nThank you for joining the Fally FashionHub Contest! Stay tuned for updates.";

    // Send the email
 // Send email
    $mail->send();
    echo "✅ Thank you $name, your entry has been received. A confirmation email has been sent to $email.";
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
 