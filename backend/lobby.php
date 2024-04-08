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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.scss">
    <link rel="stylesheet" href="../style/lobby.scss">
    <link rel="icon" type="image/svg+xml" href="/~rabah.cherak/LexiQuest-bis/websocket/frontend/dist/favicon.png" />
    <title>LexiQuest</title>
</head>

<body>
    <div class="logo right">
        <img src="../data/img/logo.png" alt="logo" />
    </div>
    <div class="container">
        <div class="lobby">
            <div class="title">
                <h1>Modes de jeu</h1>
                <hr>
            </div>
            <div class="content">
                <a href="../frontend/game.php">
                    <div class="item">
                        <img src="../data/img/solo.png" alt="">
                        Solo
                    </div>
                </a>
                <a href="../websocket/frontend/dist">
                    <div class="item">
                        <img src="../data/img/multi.png" alt="">
                        Coop
                    </div>
                </a>

            </div>
        </div>

    </div>
    <a href="../frontend/home.php">
        <div class="back"></div>
    </a>
</body>
<script>
    const existingCount = localStorage.getItem('count');

    if (existingCount !== null) {
        localStorage.removeItem('count');
    }
</script>

</html>