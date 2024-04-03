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
            if (isset($_POST['password']) && isset($_POST['password-repeat'])) {
                $changes_info = array();
                if (isset($_POST['username'])) {
                    $changes_info[] = $_POST['username'];
                } else {
                    $changes_info[] = null;
                }
                if (isset($_POST['email'])) {
                    $changes_info[] = $_POST['email'];
                } else {
                    $changes_info[] = null;
                }
                if (isset($_POST['birth-year'])) {
                    $changes_info[] = $_POST['birth-year'];
                } else {
                    $changes_info[] = null;
                }
                $changes_info[] = $_POST['password'];
                $changes_info[] = $_POST['password-repeat'];
                $status = $user->updateUser($changes_info[0], $changes_info[1], $changes_info[2], $changes_info[3], $changes_info[4]);
                $_SESSION['user'] = $user;
                $pwd_status = -1;
            }

            if (isset($_POST["new-password"]) && isset($_POST["new-password-repeat"]) && isset($_POST["old-password"])) {
                $changes_info = array();
                $changes_info[] = $_POST["new-password"];
                $changes_info[] = $_POST["new-password-repeat"];
                $changes_info[] = $_POST["old-password"];
                $pwd_status = $user->updatePassword($changes_info[0], $changes_info[1], $changes_info[2]);
                $_SESSION['user'] = $user;
                $status = -1;
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
    <link rel="stylesheet" href="../style/profile.scss">
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
                if (isset($status)) {
                    $msg = "Un e-mail de confirmation vient d'être envoyé à votre adresse e-mail.";
                    $class = "error";
                    switch ($status) {
                        case -1:
                            $msg = "";
                            break;
                        case 2:
                            $msg = "Nom d'utilisateur ou adresse email invalide";
                            break;
                        case 4:
                            $msg = "Les mots de passe ne sont pas identiques";
                            break;
                        case 5:
                            $msg = "Mot de passe invalide";
                            break;
                        default:
                            $class = "correct";
                            break;
                    }
                    echo "<h3 class=$class> $msg </h3>";
                }
                ?>
                <input type="text" name="username" minlength="3" maxlength="24"
                    placeholder="Nom d'utilisateur (Actuel : <?php echo $user->getUsername(); ?>)" />
                <input type="email" name="email"
                    placeholder="Adresse email (Actuel : <?php echo $user->getEmail(); ?>)" />
                <input type="number" name="birth-year" min="<?php echo date('Y') - 100; ?>"
                    max="<?php echo date('Y') - 12; ?>"
                    placeholder="Année de naissance (Actuel : <?php echo $user->getBirthYear(); ?>)">
                <input type="password" name="password" placeholder="Mot de passe requis pour confirmer la modification"
                 required/>
                <input type="password" name="password-repeat" placeholder="Reconfirmez votre mot de passe" required/>
                <input type="submit" value="Mettre à jour le profil" />
            </form>
        </div>
        <div class="wrapper">
            <form method="POST">
                <div class="title">
                    <h1>Mot de passe</h1>
                    <hr>
                </div>
                <?php
                if (isset($pwd_status)) {
                    $msg = "Un e-mail de confirmation vient d'être envoyé à votre adresse e-mail.";
                    $class = "error";
                    switch ($pwd_status) {
                        case -1:
                            $msg = "";
                            break;
                        case 1:
                            $class = "warning";
                            $msg = "Le nouveau mot de passe est identique à l'ancien";
                            break;
                        case 2:
                            $msg = "Nom d'utilisateur ou adresse email invalide";
                            break;
                        case 3:
                            $msg = "Le nouveau mot de pase ne respecte pas les critères de sécurité";
                            break;
                        case 4:
                            $msg = "Les mots de passe ne sont pas identiques";
                            break;
                        case 5:
                            $msg = "Ancien mot de passe invalide";
                            break;
                        default:
                            $class = "correct";
                            break;
                    }
                    echo "<h3 class=$class> $msg </h3>";
                }
                ?>
                <input type="password" name="new-password" placeholder="Nouveau mot de passe"
                 required/>
                <input type="password" name="new-password-repeat" placeholder="Confirmez votre nouveau mot de passe" required/>
                <input type="password" name="old-password" placeholder="Ancien mot de passe requis pour confirmer la modification"
                 required/>
                <input type="submit" value="Mettre à jour le profil" />
            </form>
        </div>
    </div>
    <a href="profile.php">
        <div class="back"></div>
    </a>
</body>

</html>