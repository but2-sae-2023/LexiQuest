<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once ("../class/user.php");
session_start();
$isSubmitted = false;
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}

if (isset($_POST['user']) && isset($_POST['pwd']) && isset($_POST['email']) && isset($_POST['birth-year'])) {

    $user = new User();
    $signup_output = $user->signup($_POST['user'], $_POST['pwd'], $_POST['email'], $_POST['birth-year']);
    $isSubmitted = true;
    if ($signup_output == "mail sended") {
        $_SESSION['signup'] = $signup_output;
        header('location: ../index.php');
    }
}
?>
<!Doctype HTML>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.scss">
    <link rel="stylesheet" href="../style/registration.scss">
    <title>Inscription</title>
</head>

<body>
    <div class="logo right">
        <img src="../data/img/logo.png" alt="logo" />
    </div>
    <div class="container">
        <div class="wrapper">
            <form method="post">
                <div class="title">
                    <h1>Inscription</h1>
                    <hr>
                </div>
                <h3 class="error"></h3>
                <?php
                if (isset($user)) {
                    if (!$user) {
                        echo "<h3 class='error'>Votre nom d'utilisateur ou votre mot de passe est incorrect.</h3>";
                    }
                }
                ?>
                <input type="text" name="user" placeholder="Nom d'utilisateur" minlength="3" maxlength="24" required>
                <input type="email" name="email" placeholder="Adresse email" required>
                <input type="number" name="birth-year" placeholder="Année de naissance"
                    min="<?php echo date('Y') - 100; ?>" max="<?php echo date('Y') - 12; ?>" required>
                <input type="password" name="pwd" placeholder="Mot de passe"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                <input type="password" name="pwdConfirmation" placeholder="Confirmer mot de passe"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                <input type="submit" id="submit" value="S'INSCRIRE" />
            </form>
            <div class="options">
                <span>Vous avez déjà un compte ?</span>
                <a href="../index.php">Se connecter</a>
            </div>
        </div>
    </div>
    <a href="../index.php">
        <div class="back"></div>
    </a>
</body>
<script>
    var errorDisplay = document.querySelector('.error');
    var errorOrNot = "<?php echo $signup_output == "mail sended" ? false : true; ?>";
    var isSubmitted = "<?php echo $isSubmitted; ?>";
    if (errorOrNot && isSubmitted) {
        errorDisplay.style.display = "block";
        errorDisplay.innerText = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
    } else {
        errorDisplay.style.display = "none";
    }
</script>

</html>