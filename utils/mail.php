<?php

require $_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"]);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions

function send_mail($recipient,$verificationCode,$body){

    $mail = new PHPMailer(true);
    $password = $_ENV["mail_password"];
    $email = $_ENV["mail_id"];
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $email;                     //SMTP username
        $mail->Password   = $password;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email, 'PHPAuth');
        $mail->addAddress($recipient);     //Add a recipient
        
        $description = $body["description"];
        $link_code = $body["link_code"];
        $body_mail = $body["body"];

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification';
        $mail->Body    = "
            
            <h3>Hey There.</h3>
            <i>$description</i>
            <br>
            $body_mail
            <h3>$link_code</h3>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }

}