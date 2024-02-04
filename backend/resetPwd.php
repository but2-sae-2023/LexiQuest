<?php
include_once("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    unset($_GET['token']);
}
if (isset($_POST['newPwd']) && isset($_POST['confPwd'])) {
    if ($_POST['newPwd'] != $_POST['confPwd']) {
        exit();
    }
    if (!isset($token)) {
        header("Location: ../index.php");
    }
    $user = new User();
    $newPwd = $_POST['newPwd'];
    $user->checkToken($token, "reset", $newPwd);
    $_SESSION["resetPwd"] = true;
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Réinitialiser le mot de passe</title>
        <link rel="stylesheet" href="../style/style.css">
    </head>

    <body>
        <div class="container">
            <form method="post">
                <h1>Réinitialiser le mot de passe</h1>
                <?php
                if (isset($_POST['newPwd']) && isset($_POST['confPwd'])) {
                    if ($_POST['newPwd'] != $_POST['confPwd']) {
                        echo "<p class='red'>Les mots de passe ne correspondent pas</p>";
                    }
                }
                ?>
                <div class="input">
                    <input type="password" name="newPwd" id="newPwd" placeholder="Nouveau" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                </div>
                <div class="input">
                    <input type="password" name="confPwd" id="confPwd" placeholder="Confirmer" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                </div>
                <input type="submit" id="submit" value="CONFIRMER" />
            </form>
        </div>
    </body>

</html>
