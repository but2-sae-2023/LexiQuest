<?php
include_once ("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['birth-year']) && isset($_POST['password']) && isset($_POST['password-repeat'])) {

                $status = $user->updateUser($_POST['username'], $_POST['email'], $_POST['birth-year'], $_POST['password'], $_POST['password-repeat']);
                $_SESSION['user'] = $user;
            }
        }
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
    <link rel="stylesheet" href="../style/style.scss" />
    <link rel="stylesheet" href="../style/registration.scss">
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <form method="POST">
                <div class="title">
                    <h1>Modification</h1>
                    <hr>
                </div>
                <?php
                if ($status) {
                    $msg = "Un e-mail de confirmation vient d'être envoyé à votre adresse e-mail.";
                    $class = "error";
                    switch($status) {
                        case 2: $msg = "Nom d'utilisateur ou adresse email invalide"; break;
                        case 4: $msg = "Les mots de passe ne sont pas identiques"; break;
                        case 5: $msg = "Mot de passe invalide";break;
                        default: $class = "correct"; break;
                    }
                    echo "<h3 class=$class> $msg </h3>";
                }
                ?>
                <input type="text" name="username" minlength="3" maxlength="24"
                    placeholder="Nom d'utilisateur (Actuel : <?php echo $user->getUsername(); ?>)"
                    value="<?php echo $user->getUsername(); ?>" />
                <input type="email" name="email" placeholder="Adresse email (Actuel : <?php echo $user->getEmail(); ?>)"
                    value="<?php echo $user->getEmail(); ?>" />
                <input type="number" name="birth-year" placeholder="Année de naissance"
                    min="<?php echo date('Y') - 100; ?>" max="<?php echo date('Y') - 12; ?>"
                    placeholder="Année de naissance (Actuel : <?php echo $user->getBirthYear(); ?>)"
                    value="<?php echo $user->getBirthYear(); ?>" required>
                <input type="password" name="password" placeholder="Mot de passe requis pour confirmer la modification"
                    required />
                <input type="password" name="password-repeat" placeholder="Reconfirmez votre mot de passe" required />
                <input type="submit" value="Mettre à jour le profil" />
            </form>
        </div>
    </div>
    <a href="profile.php">
        <div class="back"></div>
    </a>
</body>

</html>