<?php

namespace SafePHP;
use SafePHP\Sanitize;
use SafePHP\Secret;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

/**
 * Send mail with SMTP server
 */
class Mail {

    /**
     *  Filter mail input
     * @param string $mail mail adress to filter
     * @return mixed
     */
    public static function sanitizeMail($mail){
        return Sanitize::sanitize($mail, "email");
    }

    /**
     * Safe function to send mail with PHPMailer
     * @param mixed $from the mail that send the content
     * @param mixed $to the mail that receive the content
     * @param mixed $title header of the content
     * @param mixed $mailContent mail content
     * @return void
     */
    public static function sendMail($from, $to, $title, $mailContent){
        $mailToSanitize = self::sanitizeMail($to);
        $mailFromSanitize = self::sanitizeMail($from);
        $mail = new PHPMailer(true);
        try {
            /*Get .env file with keys*/
            $secret = new Secret(__DIR__);
            $secret->getEnv();

            /* Server settings*/
            $mail->isSMTP();
            $mail->Host = $_ENV["SMTP_HOST"]; //SMPT server to send through
            $mail->SMTPAuth = true; //Activate the SMTP authentification

            $mail->Password = $_ENV["SMTP_PASSWORD"]; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = $_ENV["SMTP_PORT"]; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($mailFromSanitize, 'Mailer');
            $mail->addReplyTo($mailToSanitize, 'Information');

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $title;
            $mail->Body = $mailContent;
            $mail->AltBody = $mailContent; //Plain text for non-HTML mail clients'

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}