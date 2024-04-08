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
    <link rel="icon" type="image/svg+xml" href="/~rabah.cherak/LexiQuest-bis/websocket/frontend/dist/favicon.png" />
    <title>LexiQuest</title>
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
                <input type="submit" id="submit" value="SE CONNECTER" disabled />

            </form>
            <div class="options">
                <span>Vous n'avez pas de compte ?</span>
                <a href="backend/signup.php">S'inscrire</a>
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
</body>

<script>
    const RequisMdp = {
        caracteres12: document.getElementById('caracteres12'),
        majuscule: document.getElementById('majuscule'),
        minuscule: document.getElementById('minuscule'),
        caractereSpecial: document.getElementById('caractereSpecial'),
        chiffre: document.getElementById('chiffre')
    };

    const pwdInput = document.getElementById('pwd');
    const requirementsList = document.getElementById('requirements');
    const submitButton = document.getElementById('submit');

    pwdInput.addEventListener('input', () => {
        validatePassword(pwdInput.value);

        /* ------------------------------------ Pour les tests ----------------------------------- */

        if(pwdInput.value == 'toto') submitButton.removeAttribute('disabled');
        if(pwdInput.value == 'totototototo') submitButton.removeAttribute('disabled');
    });

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