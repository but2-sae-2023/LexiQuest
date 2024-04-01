<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once ("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    unset($_GET['token']);
}
if (isset($_POST['newPwd']) && isset($_POST['confPwd'])) {
    if (!isset($token)) {
        header("Location: ../index.php");
    }
    $user = new User();
    $newPwd = $_POST['newPwd'];
    $user->checkToken($token, "reset", $newPwd);
    $_SESSION["resetPwd"] = true;

    if ($_POST['newPwd'] == $_POST['confPwd']) {
        header("Location: ../index.php");
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="../style/style.scss">
    <link rel="stylesheet" href="../style/registration.scss">
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <form method="POST">
                <div class="title">
                    <h1>Réinitialiser le mot de passe</h1>

                    <hr>
                </div>
                <?php
                if (isset($_POST['newPwd']) && isset($_POST['confPwd'])) {
                    if ($_POST['newPwd'] != $_POST['confPwd']) {
                        echo "<h3 class='error'>Les mots de passe ne correspondent pas</h3>";
                    }
                }
                ?>
                <input type="password" name="newPwd" id="newPwd" placeholder="Nouveau mot de passe"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                <input type="password" name="confPwd" id="confPwd" placeholder="Confirmer mot de passe"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                <input type="submit" id="submit" value="CONFIRMER" />
            </form>
        </div>
    </div>
</body>

</html>