<?php
include_once("class/user.php");
session_start();
$_SESSION['backend'] = false;
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']->checkIfConnected()) {
        echo "aa";
        header('location: ./backend/home.php'); 
    }
}
if (isset($_POST['user']) && isset($_POST['pwd'])) {
    $user = new User();
    $user = $user->connect($_POST['user'], $_POST['pwd']);
    if ($user) {
        $_SESSION['user'] = $user;
        header('location: ./backend/home.php');
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Connexion</title>
</head>

<body>
    <div class="container">
        <form method="post">
            <h1>Connexion</h1>
            <?php
                if (isset($user)) {
                  if (!$user) {
                    echo "<h3 class='red'>Il est possible que votre nom d'utilisateur ou votre mot de passe soit incorrect.</h3>";
                    }  
                }
                
                if (isset($_SESSION['signup'])) {
                    echo "<h3 class='green'>Veuillez terminer votre inscription en confirmant votre adresse e-mail à l'aide du message que vous avez reçu</h3>";
                    unset($_SESSION['signup']);
                }
                if (isset($_SESSION['forgotPwd'])) {
                    echo "<h3 class='green'>Veuillez terminer votre réinitialisation de mot de passe en confirmant votre adresse e-mail à l'aide du message que vous avez reçu</h3>";
                    unset($_SESSION['forgotPwd']);
                }
                if (isset($_SESSION['resetPwd'])) {
                    echo "<h3 class='green'>Votre mot de passe a bien été réinitialisé</h3>";
                    unset($_SESSION['resetPwd']);
                }
            ?>
            <div class="input">
                <input type="text" name="user" id="user" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="input">
                <input type="password" name="pwd" id="pwd" placeholder="Mot de passe" required>
                <p><a href="backend/forgotPwd.php">Mot de passe oublié ?</a></p>
            </div>
            <input type="submit" id="submit" value="SE CONNECTER" />
            <div class="options">
                <p>Vous n'avez pas de compte ? <br> <a href="backend/signup.php">S'inscrire</a></p>
            </div>
        </form>
    </div>
</body>

</html>