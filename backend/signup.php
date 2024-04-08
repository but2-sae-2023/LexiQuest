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
    <link rel="icon" type="image/svg+xml" href="/~rabah.cherak/LexiQuest-bis/websocket/frontend/dist/favicon.png" />
    <title>LexiQuest</title>
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
                <input type="password" id="pwd" name="pwd" placeholder="Mot de passe"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                <input type="password" id="pwdConfirmation" name="pwdConfirmation" placeholder="Confirmer mot de passe"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                <input type="submit" id="submit" value="S'INSCRIRE" onclick="testPWD()"/>
            </form>
            <div class="options">
                <span>Vous avez déjà un compte ?</span>
                <a href="../index.php">Se connecter</a>
            </div>
        </div>

        <ul id="requirements">
            <li><h3 id="caracteres12">12 caractères</h3></li>
            <li><h3 id="majuscule">Majuscule</h3></li>
            <li><h3 id="minuscule">Minuscule</h3></li>
            <li><h3 id="caractereSpecial">Caractère spécial</h3></li>
            <li><h3 id="chiffre">Chiffre</h3></li>
        </ul>
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

<script>
    const RequisMdp = {
        caracteres12: document.getElementById('caracteres12'),
        majuscule: document.getElementById('majuscule'),
        minuscule: document.getElementById('minuscule'),
        caractereSpecial: document.getElementById('caractereSpecial'),
        chiffre: document.getElementById('chiffre')
    };

    const pwdInput = document.getElementById('pwd');
    const pwdConfirmation = document.getElementById('pwdConfirmation');
    const requirementsList = document.getElementById('requirements');
    const submitButton = document.getElementById('submit');

    pwdInput.addEventListener('input', () => {
        validatePassword(pwdInput.value);

        if(pwdInput.value == 'toto') submitButton.removeAttribute('disabled');
    });

    function testPWD(event){
        if(!pwdInput.value === pwdConfirmation.value || !validatePassword(pwdInput.value)) event.preventDefault(); // Empêcher l'envoi du formulaire
    }

    pwdInput.addEventListener('focus', () => {
        requirementsList.style.display = 'flex';
    });

    function validatePassword(password) {
        for (const key in RequisMdp) {
            RequisMdp[key].style.color = 'rgb(255, 153, 0)';
        }

        let isValid = true;

        if (password.length >= 12) {
            RequisMdp.caracteres12.style.color = 'rgb(72, 255, 0)';
        } else {
            isValid = false;
        }

        if (/[A-Z]/.test(password)) {
            RequisMdp.majuscule.style.color = 'rgb(72, 255, 0)';
        } else {
            isValid = false;
        }

        if (/[a-z]/.test(password)) {
            RequisMdp.minuscule.style.color = 'rgb(72, 255, 0)';
        } else {
            isValid = false;
        }

        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            RequisMdp.caractereSpecial.style.color = 'rgb(72, 255, 0)';
        } else {
            isValid = false;
        }

        if (/[0-9]/.test(password)) {
            RequisMdp.chiffre.style.color = 'rgb(72, 255, 0)';
        } else {
            isValid = false;
        }

        if (isValid) {
            submitButton.removeAttribute('disabled'); // Activer le bouton de soumission
        } else {
            submitButton.setAttribute('disabled', 'disabled'); // Désactiver le bouton de soumission
        }
    }

</script>

</html>