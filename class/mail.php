<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../libraries/PHPMailer/src/Exception.php';
require '../../libraries/PHPMailer/src/PHPMailer.php';
require '../../libraries/PHPMailer/src/SMTP.php';

Class Mail {
    private $to;
    private $mailType;

    private $phpMailer;

    public function __construct($to) {
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email");
        }
        include_once "../private/mail_informations.php";
        $this->phpMailer = new PHPMailer();
        $this->phpMailer->IsSMTP();
        $this->phpMailer->Host = $host;        //Adresse IP ou DNS du serveur SMTP
        $this->phpMailer->Port = 465;                          //Port TCP du serveur SMTP
        $this->phpMailer->SMTPAuth = 1;                        //Utiliser l'identification
        $this->phpMailer->CharSet = 'UTF-8';

        if($this->phpMailer->SMTPAuth){
        $this->phpMailer->SMTPSecure = 'ssl';               //Protocole de sécurisation des échanges avec le SMTP
        $this->phpMailer->Username   =  $my_mail;    //Adresse email à utiliser
        $this->phpMailer->Password   =  $mail_pwd;         //Mot de passe de l'adresse email à utiliser
        }

        $this->phpMailer->From       = $my_mail;                //L'email à afficher pour l'envoi  
        $this->phpMailer->FromName   = $my_mail;          //L'alias de l'email de l'emetteur

        $this->phpMailer->AddAddress(trim($to));
        $this->phpMailer->Subject    =  "";  //Le sujet du mail
        $this->phpMailer->WordWrap   = 50;
        //Nombre de caracteres pour le retour a la ligne automatique
        $body = "";
        $this->phpMailer->Body = $body;
        $this->phpMailer->IsHTML(true);
        $this->phpMailer->MsgHTML($body);            //Forcer le contenu du body html pour ne pas avoir l'erreur "body is empty' même si on utilise l'email en contenu alternatif
    }

    public function send(User $user, $mailType, $newMail = null) {
        if ($mailType == "confirm" || $mailType == "reset" || $mailType == "new_mail" || $mailType == "old_mail" || $mailType == "confirm_pwd" || $mailType == "new_username") {
            switch($mailType) {
                case "confirm":
                    self::setBody(self::confirmMessage($user));
                    self::setSubject("Confirmation de votre inscription");
                    break;
                case "reset":
                    // $this->phpMailer->body = self::resetMessage($user);
                    // $this->subject = "Réinitialisation de votre mot de passe";
                    self::setBody(self::resetMessage($user));
                    self::setSubject("Réinitialisation de votre mot de passe");
                    break;
                case "new_mail":
                    // $this->body = self::changeNewMailMessage($user);
                    // $this->subject = "Confirmation du changement de mail";
                    self::setBody(self::changeNewMailMessage($user));
                    self::setSubject("Confirmation du changement de mail");
                    break;
                case "old_mail":
                    // $this->body = self::changeOldMailMessage($user);
                    // $this->subject = "Notification de changement de mail";
                    self::setBody(self::changeOldMailMessage($user));
                    self::setSubject("Notification de changement de mail");
                    break;
                case "confirm_pwd":
                    // $this->body = self::confirmPwdMessage($user);
                    // $this->subject = "Confirmation de la modification de votre mot de passe";
                    self::setBody(self::confirmPwdMessage($user));
                    self::setSubject("Confirmation de la modification de votre mot de passe");
                    break;
                case "new_username":
                    // $this->body = self::newUsernameMessage($user);
                    // $this->subject = "Notification de la modification de votre nom d'utilisateur";
                    self::setBody(self::newUsernameMessage($user));
                    self::setSubject("Notification de la modification de votre nom d'utilisateur");
                    break;
            }
            return $this->phpMailer->Send();

        } else {
            throw new Exception("Invalid mail type");
        }
    }

    private function confirmMessage(User $user) {
        return "
        <!Doctype HTML>
        <html>
            <head>
                <style>
                    body {
                        color: red;
                    }
                </style>
            </head>
            <body>
                <h1>Confirmation de votre inscription</h1>
                <p>Bonjour " . $user->getUsername() . ",</p>
                <p>Veuillez cliquer sur le lien ci-dessous pour confirmer votre inscription :</p>
                <a href=\"https://perso-etudiant.u-pem.fr/~rabah.cherak/LexiQuest/backend/confirmation.php?token=" . $user->getToken() . "&&type_of=signup\">Confirmer mon inscription</a>
            </body>
        </html>";
    }

    private function resetMessage(User $user) {
        return "
        <!Doctype HTML>
        <html>
            <head>
                <style>
                    body {
                        color: red;
                    }
                </style>
            </head>
            <body>
                <h1>Réinitialisation de votre mot de passe</h1>
                <p>Bonjour " . $user->getUsername() . ",</p>
                <p>Veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                <a href=\"https://perso-etudiant.u-pem.fr/~rabah.cherak/LexiQuest/backend/resetPwd.php?token=" . $user->getToken() . "&&type_of=confirm_signup\">Réinitialiser mon mot de passe</a>
            </body>
        </html>";
    }

    public function changeNewMailMessage(User $user) {
        return "
        <!Doctype HTML>
        <html>
            <head>
                <style>
                    body {
                        background-color: #f3f3f3;
                    }
                </style>
            </head>
            <body>
                <h1>Confirmation du changement de mail</h1>
                <p>Bonjour " . $user->getUsername() . ",</p>
                <p>Veuillez cliquer sur le lien ci-dessous pour confirmer votre changement de mail :</p>
                <a href=\"https://perso-etudiant.u-pem.fr/~rabah.cherak/LexiQuest/backend/confirmation.php?token=" . $user->getToken() . "&&type_of=change_mail\">Confirmer le mail</a>
            </body>
        </html>
        ";
    }

    public function changeOldMailMessage(User $user) {
        return "
        <!Doctype HTML>
        <html>
            <head>
                <style>
                    body {
                        background-color: #f3f3f3;
                    }
                </style>
            </head>
            <body>
                <h1>Notification de changement de mail</h1>
                <p>Bonjour " . $user->getUsername() . ",</p>
                <p>Nous vous informons qu'un changement de mail de votre compte a été demandé</p>
                <p>Si vous n'êtes pas à l'origine de cette demande, veuillez cliquer </p>
                <a href=\"https://perso-etudiant.u-pem.fr/~rabah.cherak/LexiQuest/backend/confirmation.php?token=" . $user->getToken() . "&&type_of=cancel_mail\">ici</a>
            </body>
        </html>
        ";
    }

    public function confirmPwdMessage(User $user) {
        return "
        <!Doctype HTML>
        <html>
            <head>
                <style>
                    body {
                        background-color: #f3f3f3;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                </style>
            </head>
            <body>
                <h1>Notification de la modification de votre mot de passe</h1>
                <p>Bonjour " . $user->getUsername() . ",</p>
                <p>Nous vous informons que votre mot de passe vient d'être modifié.</p></br><p>Si vous n'êtes pas à l'origine de ce changement, veuillez nous contacter via le mail suivant : service-client@leximail.com</p>
            </body>
        </html>
        ";
    }

    public function newUsernameMessage(User $user) {
        return "
        <!Doctype HTML>
        <html>
            <head>
                <style>
                    body {
                        background-color: #f3f3f3;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                </style>
            </head>
            <body>
                <h1>Notification de la modification de votre nom d'utilisateur</h1>
                <p>Bonjour " . $user->getUsername() . ",</p>
                <p>Nous vous informons que votre login vient de changer.</p></br><p>Si vous n'êtes pas à l'origine de ce changement, veuillez nous contacter via le mail suivant : service-client@leximail.fr</p>
            </body>
        </html>
        ";
    }

    public function setTo($to) {
        $this->phpMailer->AddAddress(trim($to));
    }

    public function setSubject($subject) {
        $this->phpMailer->Subject = $subject;
    }

    public function setBody($body) {
        $this->phpMailer->Body = $body;
    }
}

?>