<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    <link rel="icon" type="image/svg+xml" href="/~rabah.cherak/LexiQuest-bis/websocket/frontend/dist/favicon.png" />
    <title>LexiQuest</title>
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
            let count = localStorage.getItem('count') || 150;

            async function fetchData() {
                const response = await fetch('../backend/game.php');
                const data = await response.json();
                window.data = data.content;
                window.nodes = data.nodes;
                window.words = data.words;
                window.target = parseInt(data.target);
                window.current = data.current;
                let script = document.body.appendChild(document.createElement('script'));
                script.src = '../script/graphic.js';
                decompte(count);
                return [window.words, window.target, window.current];
            }
        </script>
    </div>
    <a href="../frontend/home.php">
        <div class="back"></div>
    </a>

    <div class="words">
    </div>
    <div class="score">
        Objectif
        <div class="objective">
        </div>
        Actuel
        <div class="current">
        </div>
    </div>
    <div class="words-info">
        Départ
        <div class="start">
        </div>
        Arrivée
        <div class="end">
        </div>
    </div>

    <!-- Limite de temps -->
    <div class="displayTime">
        <h5>Temps restant:</h5>
        <p id="countdown"></p>
    </div>
    
    <script>
        const countdownElement = document.getElementById('countdown');
        const body = document.body;

        function decompte(temps) {
            const timer = setInterval(() => {
                const minutes = Math.floor(temps / 60); // Obtenez le nombre de minutes
                const secondes = temps % 60; // Obtenez le nombre de secondes restantes

                if (temps < 0) {
                    clearInterval(timer);
                    const objective = document.querySelector('.objective').textContent;
                    const current = document.querySelector('.current').textContent;
                    localStorage.removeItem('count');
                    window.location.href = `score.php?objectif=${encodeURIComponent(objective)}&current=${encodeURIComponent(current)}`;
                } else {
                    // Formattez les minutes et les secondes avec deux chiffres
                    const formattedMinutes = String(minutes).padStart(2, '0');
                    const formattedSecondes = String(secondes).padStart(2, '0');

                    countdownElement.textContent = `${formattedMinutes}:${formattedSecondes}`;
                    localStorage.setItem('count', temps);
                }

                temps--; // Décrémentez le temps après la mise à jour de l'affichage
            }, 1000);
            return timer;
        }
    </script>

    <script>
        window.addEventListener('load', async () => {
            try {
                const data = await fetchData();
                const words = data[0];
                const parentDiv = document.querySelector('.words');
                words.forEach(element => {
                    const div = document.createElement('div');
                    div.className = 'item';
                    div.textContent = element;
                    parentDiv.appendChild(div);
                });
                document.querySelector('.objective').textContent = data[1];
                document.querySelector('.current').textContent = data[2];
            } catch (error) {
                console.log(error);
            }
        });
    </script>
</body>


</html>