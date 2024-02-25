<?php
include_once("../class/user.php");
include_once("../class/game.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    }

    $game = new game();
    // if (isset($_POST['play'])) {
    //     $game = new game();
    //     $score = $game->playGame($user->getUserId());
    // }

    if (!isset($_SESSION['verification'])) {
        $words = array("Pomme", "Banane", "Carotte", "Chien", "Chat", "Fleur", "Guitare", "Maison", "Île", "Jungle");
        $index1 = rand(0, 9);

        do {
            $index2 = rand(0, 9);
        } while ($index1 === $index2);

        $startWord = $words[$index1];
        $endWord = $words[$index2];

//        if (file_exists("../LexiQuest/new_game.out")) {
//            if (filemtime("../LexiQuest/new_game.out") < filemtime("../LexiQuest/new_game.c")) {
//                exec("cd ../LexiQuest/ && gcc new_game.c -o new_game.out -lm 2>&1");
//            }
//        } else {
//            exec("cd ../LexiQuest/ && gcc new_game.c -o new_game.out -lm 2>&1");
//        }
//        exec("cd ../LexiQuest/ && ./new_game.out output.lex renard poule chien chat félin loup 2>&1", $output);
//        $gameFile = fopen("../LexiQuest/gameFile.txt", "r");
//        $indexes = array();
//        $indexes = explode(",", fgets($gameFile));
//        $indexes[0] = intVal($indexes[0]);
//        $indexes[1] = intVal($indexes[1]);
//        $nWords = $indexes[0];
//        $lineCouples = $indexes[1];
//        $words = fgets($gameFile);
//        $oldWords = array();
//        $oldCouples = array();
//        for ($i = 0; $i < $nWords; $i++) {
//            $oldWords[$i] =  fgets($gameFile);
//        }
//        $i = 0;
//        while (!feof($gameFile)) {
//            $oldCouples[$i] = fgets($gameFile);
//            $i++;
//        }
//        unset($couples[$i - 1]);
//
//        fclose($gameFile);
//        $couple = explode(",", $oldCouples[0]);
//        $couple_lev_score =  intval($couple[3]);
//        $couple_sem_score = intval($couple[2]);
        $linkFormatValue = 55;
//
//        $startWord = explode(",", $oldWords[0])[0];
//        $endWord = explode(",", $oldWords[1])[0];

        $data = array(
            array(
                "from" => $startWord,
                "to" => $endWord,
                "color" => 'white',
                "linkFormat" => $linkFormatValue
            ),
            array(
                "from" => $endWord,
                "to" => $startWord,
                "color" => 'white',
                "linkFormat" => $linkFormatValue
            )
        );
        $_SESSION['data'] = $data;
        $_SESSION['verification'] = "yes";

        $game->playGame($user->getUserId(), $linkFormatValue);
        $game->updateScore($user->getUserId(), $linkFormatValue);
    }

    if (isset($_POST['play'])) {
//        $user_word = $_POST['user_word'];
//        if (file_exists("../LexiQuest/add_word.out")) {
//            if (filemtime("../LexiQuest/add_word.out") < filemtime("../LexiQuest/add_word.c")) {
//                exec("cd ../LexiQuest/ && gcc add_word.c -o add_word.out -lm 2>&1");
//            }
//        } else {
//            exec("cd ../LexiQuest/ && gcc add_word.c -o add_word.out -lm 2>&1");
//        }
//        exec("cd ../LexiQuest/ && ./add_word.out output.lex " . $user_word, $output_score);
//        if (isset($_SESSION['data']) && !empty($user_word)) {
//            $existingData = $_SESSION['data'];
//            $startWord = $existingData[0]['from'];
//            $endWord = $existingData[1]['from'];
//
//            // Calcul de la longueur des mots
//            $linkFormatValue = round(max($output[2], $output[3]));
//
//            $newData = array(
//                array(
//                    "from" => $startWord,
//                    "to" => $user_word,
//                    "color" => 'white',
//                    "linkFormat" => $linkFormatValue  // Ajout de linkFormat avec la valeur calculée
//                ),
//                array(
//                    "from" => $user_word,
//                    "to" => $endWord,
//                    "color" => 'white',
//                    "linkFormat" => $linkFormatValue  // Ajout de linkFormat avec la valeur calculée
//                )
//            );
//
//            // Ajouter la nouvelle paire de mots sans remplacer les existantes
//            array_push($_SESSION['data'], $newData[0], $newData[1]);
//            $game->updateScore($user->getUserId(), $linkFormatValue);
//        }
    }
} else {
    header('location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/game.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
    <!-- <script src="../script/graphic.js"></script> -->
    <title>Partie monojoueur</title>
</head>

<body>
    <a href="home.php">
        <div class="back"></div>
    </a>
    <form method="POST">
        <div class="wrapper">
            <input type='text' name='user_word' placeholder="Insérez votre mot"/>
            <input type="submit" id="play" name="play" value=" "/>
        </div>
    </form>
    <div id="graph-container"></div>
    <script>
        var data = <?php echo json_encode($_SESSION['data']) ?>;
        console.log(data);
    </script>
    <!--    <p><a href="home.php">Retour</a></p>-->
    <script src="../script/graphic.js"></script>
</body>

</html>