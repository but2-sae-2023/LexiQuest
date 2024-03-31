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
            <form method="POST" id="form">
                <input type="text" name="userWord" placeholder="Insérez votre mot">
                <input type="submit" id="submit-word" name="submit-word" value="" />
            </form>
        </div>

        <div class="game">

        </div>
        <div id="graph-container"></div>
        <script>
            let form = document.getElementById('form');
            form.addEventListener('submit', event => {
                event.preventDefault();

                var data = new FormData(form);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../backend/game.php');
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        console.log(data);
                        window.location.reload();
                    }
                }
                xhr.send(data);
            })
        </script>
        <script>
            async function fetchData() {
                const response = await fetch('../backend/game.php');
                const data = await response.json();
                window.data = data.content;
                window.nodes = data.nodes;
                window.words = data.words;
                let script = document.body.appendChild(document.createElement('script'));
                script.src = '../script/graphic.js';
                return window.words;
            }
        </script>
    </div>
    <a href="../frontend/home.php">
        <div class="back"></div>
    </a>
    <div class="words">
    </div>
    <script>
            window.addEventListener('load', async () => {
                try {
                    const words = await fetchData();
                    const parentDiv = document.querySelector('.words');
                    words.forEach(element => {
                        const div = document.createElement('div');
                        div.className = 'item';
                        div.textContent = element;
                        parentDiv.appendChild(div);
                    });
                } catch (error) {
                    console.log(error);
                }
            });
        </script>
</body>

</html>