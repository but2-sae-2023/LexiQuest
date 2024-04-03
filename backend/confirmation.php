<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once ("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_GET["token"]) && isset($_GET["type_of"])) {
    $user = new User();
    $type_of = htmlspecialchars($_GET["type_of"]);
    $token = htmlspecialchars($_GET["token"]);
    switch ($type_of) {
        case "signup":
            $output = $user->checkToken($token, "signup");
            if ($output == "correct signup") {
                $output = "<h3 class='correct'>Votre inscription à bien été confirmé !</h3>";
            } else {
                $output = "<h3 class='error'>Erreur, le lien n'existe pas, veuillez réessayez.</h3>";
            }
            break;
        case "reset":
            $output = $user->checkToken($token, "reset");
            if ($output == "password changed") {
                $output = "<h3 class='correct'>Votre mot de passe à bien été réinitialisé !</h3>";
            } else {
                $output = "<h3 class='error'>Erreur, le lien n'existe pas, veuillez réessayez.</h3>";
            }
            break;
        case "change_mail":
            $output = $user->checkToken($token, "change_mail");
            if ($output == "mail changed") {
                $output = "<h3 class='correct'>Votre adresse mail à bien été modifié !</h3>";
            } else {
                $output = "<h3 class='error'>Erreur, le lien n'existe pas, veuillez réessayez.</h3>";
            }
            break;
        case "cancel_mail":
            $output = $user->checkToken($token, "cancel_mail");
            if ($output == "mail change canceled") {
                $output = "<h3 class='correct'>Le changement de mail a bien été annulé</h3>";
            } else {
                $output = "<h3 class='error'>Erreur, le lien n'existe pas, veuillez réessayez.</h3>";
            }
            break;
        default:
            header('location: ../index.php');
            break;
    }
} else {
    header('location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.scss">
    <link rel="stylesheet" href="../style/registration.scss">
    <link rel="stylesheet" href="../style/home.scss">
    <title>Confirmation</title>
</head>

<body>
    <div class="container">
        <div class="home">
            <div class="content">
                <div class="title">
                    <h1>Confirmation</h1>
                    <hr>
                </div>
                <div class="item">
                    <?php
                    if (isset($output)) {
                        echo $output;
                    }
                    ?>
                    <a href="../index.php"><button>Retour à l'accueil</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>