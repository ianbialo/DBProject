<?php
namespace dbproject\conf;

require_once "vendor/phpmailer/src/PHPMailer.php";
require_once "vendor/phpmailer/src/SMTP.php";
require_once "vendor/phpmailer/src/Exception.php";
use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public static function sendMail($to,$subject,$msg)
    {
        /**$subject = "Selection At Google oueoue";
        $txt = "Congratulations blablabla";
        $headers = "From: SupervisionDB@demathieu-bard.fr";
        return mail($to,$subject,$txt,$headers);*/
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
        $mail->Host = "smtp.office365.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
        $mail->Port = 587; // TLS only
        $mail->SMTPSecure = 'tls'; // ssl is depracated
        $mail->SMTPAuth = true;
        $mail->Username = "SupervisionDB@demathieu-bard.fr";
        $mail->Password = "Passw0rd15";
        $mail->setFrom("SupervisionDB@demathieu-bard.fr");
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->msgHTML($msg); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
        $mail->AltBody = 'HTML messaging not supported';
        $mail->CharSet = 'UTF-8';
        // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
        return $mail->send();
    }
}
