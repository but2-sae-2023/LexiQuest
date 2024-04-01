<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once ("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user_id = $user->getUserId();
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    }
} else {
    header('location: ../index.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.scss" />
    <link rel="stylesheet" href="../style/profile.scss" />
    <title>Profil</title>
</head>

<body>
    <div class="logo center">
        <img src="../data/img/logo.png" alt="logo" />
    </div>
    <div class="container">
        <div class="username"><?php echo $user->getUsername() ?></div>
        <div class="profile">
            <div class="content">
                <div class="title">
                    <h1>Informations
                        <a href="../backend/edit-profile.php">
                            <div class="edit"></div>
                        </a>
                    </h1>
                    <hr>
                </div>
                <div class="item">Adresse email<span><?php echo $user->getEmail()?></span></div>
                <div class="item">Année de naissance<span><?php echo $user->getBirthYear()?></span></div>
                <?php 
                    $signupDate = $user->getSignupDate();
                    $signupDate = date("d-m-Y", strtotime($signupDate));
                ?>
                <div class="item">Date d'inscription<span><?php echo $signupDate ?></span></div>
            </div>
        </div>
        <div class="profile">
            <div class="content">
                <div class="title">
                    <h1>Statistiques</h1>
                    <hr>
                </div>
                <div class="item">Nombre de parties jouées<span><?php echo $user->getNumberGamesPlayed($user_id) ?></span></div>
            </div>
        </div>
    </div>

    <a href="../frontend/home.php">
        <div class="back"></div>
    </a>
</body>

</html>