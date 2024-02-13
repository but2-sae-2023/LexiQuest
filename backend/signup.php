<?php
include_once("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }

if (isset($_POST['user']) && isset($_POST['pwd']) && isset($_POST['email']) && isset($_POST['birth-year'])) {

    $user = new User();
    $signup_output = $user->signup($_POST['user'], $_POST['pwd'], $_POST['email'], $_POST['birth-year']);
    if ($signup_output) {
        $_SESSION['signup'] = $signup_output;
        header('location: ../index.php');
    } else {
        echo " probleme ";
    }
}
?>
<!Doctype HTML>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Inscription</title>
</head>

<body>
    <div class="container">
        <form method="post">
            <h1>Inscription</h1>
            <div class="input">
                <input type="text" name="user" placeholder="Nom d'utilisateur" minlength="3" maxlength="24" required>
                <?php
                // if (isset($result)) {
                // 	if ($result == "username") {
                // 		echo "<h3>Le nom d'utilisateur doit contenir entre 3 et 24 caractères</h3>";
                // 	}
                // }
                ?>
            </div>
            <div class="input">
                <input type="email" name="email" placeholder="Adresse email" required>
                <?php
                // if (isset($result)) {
                // 	if ($result == "email") {
                // 		echo "<h3>L'adresse email n'est pas valide. Il vous manque surement l'extension de domaine '.com' ou '.fr' </h3>";
                // 	}
                // }
                ?>
            </div>
            <div class="input">
                <?php
                $this_year = date_create(date('Y'));
                $min_year = date_sub($this_year, date_interval_create_from_date_string("100 years"));
                $this_year = date_create(date('Y'));
                $max_year = date_sub($this_year, date_interval_create_from_date_string("12 years"));
                ?>
                <input type="number" name="birth-year" placeholder="Année de naissance" min="<?php echo date_format($min_year, "Y") ?>" max="<?php echo date_format($max_year, "Y"); ?>" required>
            </div>
            <?php
            // if (isset($result)) {
            //     if ($result == "created") {
            //         header('location: ../index.php');
            //         $_SESSION['signup'] = true;
            //     }

            // }
            ?>
            <div class="input">
                <input type="password" name="pwd" placeholder="Mot de passe" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" minlength="12" maxlength="32" required>
                <?php
                // if (isset($result)) {
                // 	if ($result == "password") {
                // 		echo "<h3>Le mot de passe doit contenir entre 12 et 32 caractères</h3>";
                // 	}
                // }
                ?>
            </div>
            <input type="submit" id="submit" value="S'INSCRIRE" />
            <div class="options">
                <p>Vous avez déjà un compte ?<br><a href="../index.php">Se connecter</a></p>
            </div>
        </form>

    </div>
</body>

</html>