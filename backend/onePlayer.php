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
        // $words = array("Pomme", "Banane", "Carotte", "Chien", "Chat", "Fleur", "Guitare", "Maison", "Île", "Jungle");
        // $index1 = rand(0, 9);

        // do {
        //     $index2 = rand(0, 9);
        // } while ($index1 === $index2);

        // $startWord = $words[$index1];
        // $endWord = $words[$index2];
        exec("cd ../LexiQuest/ && ./new_game.out output.lex chien chat camion avion transport église gâteau place voiture 2>&1", $output);
        $startWord = $output[1];
        $endWord = $output[0];
        $linkFormatValue = strlen($startWord) + strlen($endWord);

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
        $user_word = $_POST['user_word'];
        if (isset($_SESSION['data']) && !empty($user_word)) {
            $existingData = $_SESSION['data'];
            $startWord = $existingData[0]['from'];
            $endWord = $existingData[1]['from'];
    
            // Calcul de la longueur des mots
            $linkFormatValue = strlen($startWord) + strlen($user_word) + strlen($endWord);
    
            $newData = array(
                array(
                    "from" => $startWord,
                    "to" => $user_word,
                    "color" => 'white',
                    "linkFormat" => $linkFormatValue  // Ajout de linkFormat avec la valeur calculée
                ),
                array(
                    "from" => $user_word,
                    "to" => $endWord,
                    "color" => 'white',
                    "linkFormat" => $linkFormatValue  // Ajout de linkFormat avec la valeur calculée
                )
            );
    
            // Ajouter la nouvelle paire de mots sans remplacer les existantes
            array_push($_SESSION['data'], $newData[0], $newData[1]);
            exec("cd ../LexiQuest/ && ./add_word.out output.lex " . $user_word, $output);   
            $game->updateScore($user->getUserId(), $linkFormatValue);
        }
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
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
    <!-- <script src="../script/graphic.js"></script> -->
    <title>Partie monojoueur</title>
</head>

<body>
    <div id="graph-container"></div>
    <form method="POST">
        <div class="word-area">
            <input type='text' name='user_word' />
            <input type="submit" id="play" name="play" value="Jouer" />
        </div>
    </form> <br>
    <p><a href="home.php">Retour</a></p>
    <script>
        var data = <?php echo json_encode($_SESSION['data']) ?>;
        console.log(data);
    </script>
    <script src="../script/graphic.js"></script>
</body>

</html>