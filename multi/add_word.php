<?php
include_once ("../class/user.php");
include_once ("../class/game.php");
session_start();

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
    $lines = file('games/' . $id . '-game/msTree.txt');
    $words = explode(",", file('games/' . $id . '-game/gameFile.txt')[1]);

    $content = [];
    if ($_POST['nodes']) {
        $nodes = json_decode($_POST['nodes']);
    }


    foreach ($lines as $line) {
        [$word1, $word2, $score] = explode(",", $line);
        $content[] = ["from" => $word2, "to" => $word1, "linkFormat" => $score];
        $content[] = ["from" => $word1, "to" => $word2, "linkFormat" => $score];
        
        $nodes[] = ["id" => $word1, "color" => "white"];
        $nodes[] = ["id" => $word2, "color" => "white"];
    }

    echo json_encode(['content' => $content, 'nodes' => $nodes, 'words' => $words, 'state' => 200]);
}

