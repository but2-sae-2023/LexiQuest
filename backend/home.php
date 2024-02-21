<?php
    include_once("../class/user.php");
    session_start();

    if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }
    if (isset($_SESSION['verification'])) { unset($_SESSION['verification']); }
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        
        if (!$user->checkIfConnected()) {
            header('location: ../index.php');
        }
    } else {
        header('location: ../index.php');
    }
?>

<!Doctype HTML>
<html>
    <head>
        <title>Home</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../style/style.css" />
    </head>
    <body>
        <div class="container center space">
            <h1>Accueil
            </h1>
            <h3><u>Mode de jeu disponible :</u></h3>
            <a href="onePlayer.php"><button>1 joueur</button></a>
            <!-- <a href="twoPlayer.php"><button>2 joueurs</button></a> -->
            <a href="disconnect.php"><button>Déconnexion</button></a>
        </div>
        <a href="../src/compte.html">
            
            <div class="profil-container">
                <h2><?php echo $user->getUsername()?></h2>
                <div class="profil center">
                    <div class="profil-head"></div>
                    <div class="profil-body"></div>
                </div>   
            </div>
             
        </a>
    </body>
</html>
