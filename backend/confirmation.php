<?php
    include_once("../class/user.php");
    session_start();
    if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }
    if (isset($_GET["token"])) {
        $user = new User();
        $output = $user->checkToken($_GET["token"], "signup");
        if ($output == "success_signup") {
            echo "<h2 class='green'>Votre inscription à bien été confirmé !</h2>";
        } else {
            echo "<h2 style=\"color: red;\">Erreur, le lien n'existe pas, veuillez réessayez.</h2>";
        }
    } else {
        header('location: ../index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Confirmation</title>
</head>

<body>
    <div class="container center">
        <br>
        <a href="../index.php"><button>Page de connexion</button></a>
    </div>
</body>

</html>