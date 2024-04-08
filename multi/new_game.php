<?php
include_once("../class/user.php");
include_once("../class/game.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = new User();
$username = $_POST['user'];
$user_id = $user->getUserId($username);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$game = new game();
	$game->playGame($user_id);

	$id = $game->getGameId($user_id);

	chdir("../c");
	exec("./new_game.out dico.bin $id CD shérif Olympia chevelu cool chien environnement 2>&1", $output);

	[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

	chdir("../java");
	exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");

	$nodes[] = ["id" => $startWord, "color" => "#567fe8"];
	$nodes[] = ["id" => trim($endWord), "color" => "#e8a956"];
}

chdir("../c");
$path = 'games/' . $id . '-game/';
$msTree = file($path . 'msTree.txt');
$words = explode(",", file($path . 'gameFile.txt')[1]);
$target = file($path . 'best_score.txt');

$content = [];

foreach ($msTree as $line) {
	[$word1, $word2, $score] = explode(",", $line);
	$content[] = ["from" => $word2, "to" => $word1, "linkFormat" => $score];
	$content[] = ["from" => $word1, "to" => $word2, "linkFormat" => $score];
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

echo json_encode(['content' => $content, 'nodes' => $nodes, 'words' => $words, 'id' => $id, 'state' => 200, 'target' => $target, 'current' => $scores]);