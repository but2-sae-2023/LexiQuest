<?php
include "../private/mail_informations.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../libraries/PHPMailer/src/Exception.php';
require '../../libraries/PHPMailer/src/PHPMailer.php';
require '../../libraries/PHPMailer/src/SMTP.php';

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = $host;        //Adresse IP ou DNS du serveur SMTP
$mail->Port = 465;                          //Port TCP du serveur SMTP
$mail->SMTPAuth = 1;                        //Utiliser l'identification
$mail->CharSet = 'UTF-8';

if($mail->SMTPAuth){
$mail->SMTPSecure = 'ssl';               //Protocole de sécurisation des échanges avec le SMTP
$mail->Username   =  $my_mail;    //Adresse email à utiliser
$mail->Password   =  $mail_pwd;         //Mot de passe de l'adresse email à utiliser
}

$mail->From       = $my_mail;                //L'email à afficher pour l'envoi  
$mail->FromName   = $my_mail;          //L'alias de l'email de l'emetteur

$mail->AddAddress(trim($this->to));
$mail->Subject    =  $this->subject;  //Le sujet du mail
$mail->WordWrap   = 50;
//Nombre de caracteres pour le retour a la ligne automatique
$body = $this->body;
$mail->Body = $body;
$mail->IsHTML(true);
$mail->MsgHTML($body);            //Forcer le contenu du body html pour ne pas avoir l'erreur "body is empty' même si on utilise l'email en contenu alternatif

// Envoi du mail
if (!$mail->send()) {
    echo $mail->ErrorInfo;
    return false;
}
return true;

?>