<?php
include_once("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['birth-year']) && isset($_POST['password']) && isset($_POST['password-repeat'])) {

                $user = $user->updateUser($_POST['username'],$_POST['email'],$_POST['birth-year'],$_POST['password'], $_POST['password-repeat']);
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
    <link rel="stylesheet" href="../style/style.css" />
</head>

<body>
    <div class="container">
        <form method="post" action="">
            <h1>Modification du profil</h1>
            <div class="input">
                <input type="text" name="username" minlength="3" maxlength="24" placeholder="Nom d'utilisateur (Actuel : <?php echo $user->getUsername(); ?>)" value="<?php echo $user->getUsername(); ?>" />
            </div>
            <div class="input">
                <input type="email" name="email" placeholder="Adresse email (Actuel : <?php echo $user->getEmail(); ?>)" value="<?php echo $user->getEmail(); ?>" />
            </div>
            <div class="input">
                <?php
                $this_year = date_create(date('Y'));
                $min_year = date_sub($this_year, date_interval_create_from_date_string("100 years"));
                $this_year = date_create(date('Y'));
                $max_year = date_sub($this_year, date_interval_create_from_date_string("12 years"));
                ?>
                <input type="number" name="birth-year" min="<?php echo date_format($min_year, "Y") ?>" max="<?php echo date_format($max_year, "Y");?>" placeholder="Année de naissance (Actuel : <?php echo $user->getBirthYear(); ?>)" value="<?php echo $user->getBirthYear(); ?>" />
            </div>
            <div class="input">
                <label for="">Mot de passe requis pour confirmer la modification</label>
                <input type="password" name="password" placeholder="Mot de passe" required />
            </div>
            <div class="input">
                <label for="">Confirmez votre mot de passe</label>
                <input type="password" name="password-repeat" placeholder="Mot de passe" required />
            </div>
            <input type="submit" value="Mettre à jour le profil" />
            <a href="profile.php">
                <div class="back"></div>
            </a>
        </form>
    </div>

</body>

</html>