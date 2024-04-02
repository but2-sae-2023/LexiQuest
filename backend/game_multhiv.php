<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once ("../class/user.php");
include_once ("../class/game.php");
session_start();

if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }

$user = $_SESSION['user'];
$username = $user->getUsername();
$user_id = $user->getUserId();

if (isset($_SESSION['gameId'])) {
    $id = $_SESSION['gameId'];
}

if (!$_SESSION['gameRunning']) {
    $_SESSION['gameRunning'] = true;

    $game = new game();
    $game->playGame($user_id);

    $id = $game->getGameId($user_id);
    $_SESSION['gameId'] = $id;

    chdir("../c");
    exec("./new_game.out dico.bin $id fleurs leur 2>&1", $output);

    [$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

    chdir("../java");
    exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");

    $nodes[] = ["id" => $startWord, "color" => "#567fe8"];
    $nodes[] = ["id" => trim($endWord), "color" => "#e8a956"];

    $_SESSION['nodes'] = $nodes;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userWord"])) {
    $userWord = $_POST["userWord"];
    chdir("../c");
    exec("./add_word.out dico.bin $id $username $userWord");

    [$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

    chdir("../java");
    exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");

    if (!file_exists('games/' . $id . '-game/msTree.txt')) {
        http_response_code(500);
        echo json_encode(["error" => "Files not found"]);
        exit();
    }

    lines = explode("\n", file_get_contents('games/'.id.'-game/msTree.txt'));
    lines = array_filter(lines);

    words = explode(",",file('games/'.id.'-game/gameFile.txt')[1]);

    content = [];nodes = $_SESSION['nodes'];

    foreach (lines as line) {
        [word1, word2, score] = explode(",",line);
        content[] = ["from" => word2, "to" => word1, "linkFormat" => score];
        content[] = ["from" => word1, "to" => word2, "linkFormat" => score];
        if(!in_array(word1, array_column(nodes, "id"))){
            nodes[] = ["id" => word1, "color" => "white"];
        }
        if(!in_array(word2, array_column(nodes, "id"))){
            nodes[] = ["id" => word2, "color" => "white"];
        }
    }

    echo json_encode(['content' => content, 'nodes' => nodes, 'words' => $words]);

} else {
    http_response_code(400);
    echo json_encode(["error" => "Missing user word"]);
}

?>