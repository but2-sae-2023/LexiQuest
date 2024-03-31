<?php
include_once ("class/user.php");
session_start();
$_SESSION['backend'] = false;
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']->checkIfConnected()) {
        header('location: ./frontend/home.php');
    }
}
if (isset($_POST['user']) && isset($_POST['pwd'])) {
    $user = new User();
    $user = $user->connect($_POST['user'], $_POST['pwd']);
    if ($user) {
        $_SESSION['user'] = $user;
        header('location: ./frontend/home.php');
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.scss">
    <link rel="stylesheet" href="style/registration.scss">
    <title>Connexion</title>
</head>

<body>
    <div class="logo left">
        <img src="data/img/logo.png" alt="logo" />
    </div>
    <div class="container">
        <div class="wrapper">
            <form method="post">
                <div class="title">
                    <h1>Connexion</h1>
                    <hr>
                </div>
                <?php
                if (isset($user)) {
                    if (!$user) {
                        echo "<h3 class='error'>Votre nom d'utilisateur ou votre mot de passe est incorrect.</h3>";
                    }
                }
                ?>
                <input type="text" name="user" id="user" placeholder="Nom d'utilisateur" required>
                <div class="item">
                    <input type="password" name="pwd" id="pwd" placeholder="Mot de passe" required>
                    <a href="backend/forgotPwd.php">Mot de passe oublié ?</a>
                </div>
                <input type="submit" id="submit" value="SE CONNECTER" />

            </form>
            <div class="options">
                <span>Vous n'avez pas de compte ?</span>
                <a href="backend/signup.php">S'inscrire</a>
            </div>
        </div>
    </div>
</body>

</html>