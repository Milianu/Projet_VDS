<?php
require RACINE . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail {
    private object $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer;
        //Server settings
        // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp-delattre.alwaysdata.net';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'delattre@alwaysdata.net';
        $this->mail->Password   = 'SlamSr.2023';
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port = 465;
        $this->mail->setFrom('delattre@alwaysdata.net', 'admin');

    }

    /**
     * Indiquer l'expéditeur du mail (utile dans 'nous contacter')
     * @param string $email
     * @param string $nom
     * @return void
     */
    public function setExpediteur(string $email, string $nom) : void {
        $this->mail->setFrom($email, $nom);
    }


    /**
     * @param string $destinataire
     * @param string $sujet
     * @param string $message
     * @return int|string
     */

    /**
     * @param string $destinataire
     * @param string $sujet
     * @param string $message
     * @return int
     */
    public function envoyer(string $destinataire, string $sujet, string $message) : int
    {
        $this->mail->isHTML(true); // Set email format to HTML
        $this->mail->Subject = utf8_decode($sujet);
        $this->mail->Body = utf8_decode($message);
        // $this->mail->Body = $message;
        $this->mail->AltBody = utf8_decode($message);
        // $this->mail->AltBody = $message;
        $this->mail->addAddress($destinataire);
        try {
            $this->mail->send();
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * @param string $destinataire
     * @param string $sujet
     * @param string $message
     * @param string $piece
     * @return int
     */
    public function envoyerAvec(string $destinataire, string $sujet, string $message, string $piece) : int
    {
        $this->mail->isHTML(true); // Set email format to HTML
        $this->mail->Subject = utf8_decode($sujet);
        $this->mail->Body = utf8_decode($message);
        // $this->mail->Body = $message;
        $this->mail->AltBody = utf8_decode($message);
        // $this->mail->AltBody = $message;
        $this->mail->addAddress($destinataire);
        $this->mail->AddAttachment($piece);
        try {
            $this->mail->send();
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

}
