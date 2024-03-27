<?php
include_once ("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_POST['user']) && isset($_POST['email'])) {
    $username = htmlspecialchars($_POST['user']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $user = new User();
    $user->forgotPwd($username, $email);
    $_SESSION["forgotPwd"] = true;
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../style/style.scss">
    <link rel="stylesheet" href="../style/registration.scss">
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <form method="POST">
                <div class="title">
                    <h1>Mot de passe oublié</h1>
                    <hr>
                </div>
                <?php
                if (isset($user)) {
                    echo "<h3 class='correct'>Si un compte dont le nom d'utilisateur et le mail correspondent à ceux indiqués alors un mail vous sera envoyé</h3>";
                }
                ?>

                <input type="text" name="user" id="user" placeholder="Nom d'utilisateur" required>
                <input type="email" name="email" id="email" placeholder="Adresse e-mail" required>
                <input type="submit" id="submit" value="ENVOYER" />
            </form>
        </div>
    </div>
    <a href="../index.php">
        <div class="back"></div>
    </a>
</body>

</html>