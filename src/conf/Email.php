<?php
namespace dbproject\conf;

require_once "vendor/phpmailer/src/PHPMailer.php";
require_once "vendor/phpmailer/src/SMTP.php";
require_once "vendor/phpmailer/src/Exception.php";
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Classe permettant la gestion d'envoi de mail dans l'application
 *
 * @author IBIALO
 *        
 */
class Email
{

    /**
     * Méthode d'envoi de mail
     *
     * @param string $subject
     *            sujet du mail
     * @param string $msg
     *            message contenu dans le mail
     * @return boolean indicatif de réussite d'envoi de mail
     */
    public static function sendMail($subject, $msg)
    {
        if (sizeof(Variable::$annuaire) != 0) {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = "smtp.office365.com";
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = "SupervisionDB@demathieu-bard.fr";
            $mail->Password = "Passw0rd15";
            $mail->setFrom("SupervisionDB@demathieu-bard.fr");
            foreach (Variable::$annuaire as $annuaire => $nom)
                $mail->AddCC($annuaire, $nom);
            $mail->Subject = $subject;
            $mail->msgHTML($msg);
            $mail->AltBody = 'HTML messaging not supported';
            $mail->CharSet = 'UTF-8';
            return $mail->send();
        }
        return 0;
    }
}
