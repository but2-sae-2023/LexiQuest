<?php
include_once ("../class/user.php");
include_once ("../class/game.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['gameId']) && isset($_POST['username'])) {
        $id = $_POST['gameId'];
        $username = $_POST['username'];

        if (isset($_POST['userWord'])) {
            $userWord = $_POST['userWord'];
            chdir("../c");
            exec("./add_word.out dico.bin $id $username $userWord");

            [$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

            chdir("../java");
            exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");
        }
    }

    chdir("../c");
    $path = 'games/' . $id . '-game/';
    $msTree = file($path . 'msTree.txt');
    $words = explode(",", file($path . 'gameFile.txt')[1]);
    $target = file($path . 'best_score.txt');

    $content = [];
    if ($_POST['nodes']) {
        $nodes = json_decode($_POST['nodes']);
    }

    foreach ($msTree as $line) {
        [$word1, $word2, $score] = explode(",", $line);
        $content[] = ["from" => $word2, "to" => $word1, "linkFormat" => $score];
        $content[] = ["from" => $word1, "to" => $word2, "linkFormat" => $score];

        $colors = ["white", "#567fe8", "#e8a956"];

        if (!in_array($word1, array_column($nodes, 'id')) && !in_array($word2, array_column($nodes, 'id'))) {
            foreach ($colors as $color) {
                if (!in_array(["id" => $word1, "color" => $color], $nodes)) {
                    $nodes[] = ["id" => $word1, "color" => $color];
                    break;
                }
            }

            foreach ($colors as $color) {
                if (!in_array(["id" => $word2, "color" => $color], $nodes)) {
                    $nodes[] = ["id" => $word2, "color" => $color];
                    break;
                }
            }
        }

        if (file_exists($path . 'bestPath.txt')) {
            $scores = [];
            $bestPath = file($path . 'bestPath.txt');
            foreach ($bestPath as $line) {
                [$word1, $word2, $score] = explode(",", $line);
                $scores[] = $score;
            }
        } else {
            $scores = [0];
        }
    }

    echo json_encode(['content' => $content, 'nodes' => $nodes, 'words' => $words, 'state' => 200, 'id' => $userWord, "target" => $target, "current" => min($scores)]);
}


