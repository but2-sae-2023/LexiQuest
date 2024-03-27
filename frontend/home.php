<?php
include_once ("../class/user.php");
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    }
} else {
    header('location: ../index.php');
}

?>
<!DOCTYPE html>

<html>

<head>
    <title>Home</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../style/style.scss" />
    <link rel="stylesheet" href="../style/home.scss" />
    <script src="../script/dataListener.js"></script>
</head>

<body>
    <div class="container">
        <div class="home">
            <div class="content">
                <div class="title">
                    <h1>Accueil</h1>
                    <hr>
                </div>
                <div class="item">
                    <a href="game.php"><button>1 joueur</button></a>
                    <a href="../backend/trace.php"><button>Page de traces </button></a>
                    <a href="../backend/disconnect.php"><button>Déconnexion</button></a>
                </div>

            </div>
        </div>
    </div>
    <a href="../backend/profile.php">
        <div class="profile">
            <div class="icon"></div>
        </div>
    </a>
</body>

</html>