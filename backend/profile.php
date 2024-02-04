<?php
include_once("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    }
} else {
    header('location: ../index.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../style/style.css" />
</head>

<body>
    <div class="container center profil-space display-row">
        <div class="edit-profile">
            <div class="edit-container">
                <h1>Informations</h1>
                <a href="./edit-profile.php"><div class="edit-button"></div></a>
            </div>

            <hr>
            <h2>Nom d'utilisateur : <u> <?php echo $user->getUsername()?> </u></h2>
            <h2>Adresse Email : <u> <?php echo $user->getEmail()?> </u> </h2>
            <h2>Année de naissance : <u> <?php echo $user->getBirthYear()?> </u></h2>
            <h2>Date d'inscription : <u> <?php echo $user->getSignupDate()?> </u></h2>
        </div>
    </div>
    <br>
    <p><a href="trace.php"><button>Page de trace</button></a></p> <br>
    
    <div class="options">
        <a href="home.php">Retour</a>
    </div>

</body>

</html>