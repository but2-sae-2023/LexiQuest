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
    <link rel="icon" type="image/svg+xml" href="/~rabah.cherak/LexiQuest-bis/websocket/frontend/dist/favicon.png" />
    <title>LexiQuest</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../style/style.scss" />
    <link rel="stylesheet" href="../style/rules.scss" />
    <script src="../script/dataListener.js"></script>
</head>

<body>
    <div class="logo right">
        <img src="../data/img/logo.png" alt="logo" />
    </div>
    <div class="container">
        <div class="rules">
            <div class="title">
                <img src="../data/img/rules.png">
                <div class="main">
                    <h1>Règles</h1>
                    <hr>
                </div>
                <img src="../data/img/rules.png">
            </div>
            <div class="content">
                <div class="text">
                    <strong>Semantic Analogy Explorer</strong> - est un jeu en ligne passionnant où les joueurs créent
                    des chaînes de mots
                    similaires pour relier un mot de départ à un mot cible.
                </div>
                <div class="text">
                    <strong>Objectif</strong> - L'objectif du jeu est de créer une chaîne de mots où chaque mot est
                    sémantiquement et orthographiquement similaire au précédent, afin de relier le mot de départ au mot
                    cible.
                </div>
                <div class="text">
                    <strong>Similarité des mots</strong> - La similarité entre deux mots est évaluée selon deux critères
                    :
                    <div class="list">
                        • <strong>Similarité sémantique</strong> - Les mots doivent être utilisés dans le même contexte.
                        Par exemple,
                        "hautbois" et "clarinette" sont sémantiquement similaires.
                    </div>
                    <div class="list">
                        • <strong>Similarité orthographique</strong> - Les mots doivent avoir des orthographes proches.
                        Par exemple,
                        "bateau" et "château" sont orthographiquement similaires.
                    </div>
                </div>
                <div class="text">
                    <strong>Score</strong> - Le score d'une chaîne de mots correspond au score de similarité de son
                    maillon
                    le plus
                    faible. Il est donc important que chaque mot ressemble autant que possible au précédent.
                </div>
                <div class="text">
                    <strong>Compte joueur</strong> - Les joueurs doivent créer un compte pour jouer. Cela leur permet de
                    sauvegarder
                    leurs parties en cours et passées, et d'organiser des parties avec leurs contacts.
                </div>
                <div class="text">
                    <strong>Règles du jeu</strong> - Les règles précises du jeu, telles que la fin du jeu, les cibles,
                    les
                    possibilités
                    d'interactions et le mode de jeu (tour par tour ou en parallèle), peuvent varier. Ces détails seront
                    spécifiés avant le début de chaque partie.
                </div>
                Amusez-vous bien en explorant les analogies sémantiques !
            </div>
        </div>
        <!-- <div class="rules">
            <div class="content">
                <div class="title">
                    <h1>Règles</h1>
                    <hr>
                </div>
                <div class="text">

                </div>
            </div>
        </div> -->
    </div>
    <a href="../frontend/home.php">
        <div class="back"></div>
    </a>
</body>

</html>