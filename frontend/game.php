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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.scss">
    <link rel="stylesheet" href="../style/game.scss">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../script/dataListener.js"></script>

    <title>Partie monojoueur</title>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <form method="POST">
                <input type="text" name="user-word" placeholder="Insérez votre mot">
                <input type="submit" id="submit-word" name="submit-word" value="" />
            </form>
        </div>

        <div class="game">

        </div>
        <!-- <div id="graph-container"></div>
        <script>
            getData('game.php').then(data => {
                window.data = data.data;
                window.nodes = data.nodes;
                console.log(data);
                const script = document.body.appendChild(document.createElement('script'));
                script.src = '../script/graphic.js';
            });

            sendData('game.php', 'form');
        </script> -->
    </div>
    <a href="../frontend/home.php">
        <div class="back"></div>
    </a>
</body>

</html>