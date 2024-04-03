<?php
include_once("../class/user.php");
include_once("../class/game.php");
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }

$user = new User();
$username = $_POST['user'];
// $username = $user->getUsername();
$user_id = $user->getUserId($username);

// echo $username;
// echo $user_id;

// if (isset($_SESSION['gameId'])) {
// 	$id = $_SESSION['gameId'];
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$game = new game();
	$game->playGame($user_id);

	$id = $game->getGameId($user_id);
	$_SESSION['gameId'] = $id;

	chdir("../c");
	exec("./new_game.out dico.bin $id CD shérif Olympia chevelu cool chien environnement 2>&1", $output);

	[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

	chdir("../java");
	exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");

	$nodes[] = ["id" => $startWord, "color" => "#567fe8"];
	$nodes[] = ["id" => trim($endWord), "color" => "#e8a956"];

	$_SESSION['nodes'] = $nodes;
}

chdir("../c");
$lines = file('games/' . $id . '-game/msTree.txt');
$words = explode(",", file('games/'.$id.'-game/gameFile.txt')[1]);

$content = [];
$nodes = $_SESSION['nodes'];

foreach ($lines as $line) {
	[$word1, $word2, $score] = explode(",", $line);
	$content[] = ["from" => $word2, "to" => $word1, "linkFormat" => $score];
	$content[] = ["from" => $word1, "to" => $word2, "linkFormat" => $score];
	$nodes[] = ["id" => $word1, "color" => "white"];
	$nodes[] = ["id" => $word2, "color" => "white"];
}

echo json_encode(['content' => $content, 'nodes' => $nodes, 'words' => $words, 'id' => $id, 'state' => 200]);