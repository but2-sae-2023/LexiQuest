<?php 

Class Mail {
    private $to;
    private $subject;
    private $body;
    private $from = "From: ";
    private $mailType;

    public function __construct($to, $mailType) {
        if ($mailType != "confirm" && $mailType != "reset") {
            throw new Exception("Invalid mail type");
        }
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email");
        }
        $this->to = $to;
        $this->mailType = $mailType;
        if ($mailType == "confirm") {
            $this->subject = "Confirmer votre inscription";
        } else {
            $this->subject = "Réinitialisation de votre mot de passe";
        }
    }

    private function includePHPMailer() {
        return include "../backend/phpmailer.php";
    }

    public function send(User $user) {
        if ($this->mailType == "confirm") {
            return $this->confirmMail($user);
        } else {
            return $this->resetMail($user);
        }
    }

    private function confirmMail(User $user) {
        $this->body = self::confirmMessage($user);
        return self::includePHPMailer();
    }

    private function resetMail(User $user) {
        $this->body = self::resetMessage($user);
        return self::includePHPMailer();
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
                <a href=\"https://perso-etudiant.u-pem.fr/~rabah.cherak/projet_sae/backend/confirmation.php?token=" . $user->getToken() . "\">Confirmer mon inscription</a>
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
                <a href=\"https://perso-etudiant.u-pem.fr/~rabah.cherak/projet_sae/backend/resetPwd.php?token=" . $user->getToken() . "\">Réinitialiser mon mot de passe</a>
            </body>
        </html>";
    }
}

?>