<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once ("../class/user.php");
include_once ("../class/game.php");
session_start();

if (isset($_SESSION['backend'])) {
	unset($_SESSION['backend']);
}

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
	exec("./new_game.out dico.bin $id CD chiens transport machine corps nature famille chat loup animal renard 2>&1", $output);

	[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

	chdir("../java");
	exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");

	$nodes[] = ["id" => $startWord, "color" => "#567fe8"];
	$nodes[] = ["id" => trim($endWord), "color" => "#e8a956"];

	$_SESSION['nodes'] = $nodes;
}

if (isset($_POST['userWord'])) {
	$userWord = $_POST['userWord'];
	chdir("../c");
	exec("./add_word.out dico.bin $id $username $userWord");

	[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

	chdir("../java");
	exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");
}

chdir("../c");
$path = 'games/' . $id . '-game/';
$msTree = file($path . 'msTree.txt');
$words = explode(",", file($path . 'gameFile.txt')[1]);
$target = file($path . 'best_score.txt');

$content = [];
$nodes = $_SESSION['nodes'];

foreach ($msTree as $line) {
	[$word1, $word2, $score] = explode(",", $line);
	$content[] = ["from" => $word2, "to" => $word1, "linkFormat" => $score, "color" => "white"];
	$content[] = ["from" => $word1, "to" => $word2, "linkFormat" => $score, "color" => "white"];
	$nodes[] = ["id" => $word1, "color" => "white"];
	$nodes[] = ["id" => $word2, "color" => "white"];
}

if (file_exists($path . 'bestPath.txt')) {
	$scores = [];
	$bestPath = file($path . 'bestPath.txt');
	foreach ($bestPath as $line) {
		[$bpWord1, $bpWord2, $score] = explode(",", $line);
		$scores[] = $score;
		foreach ($content as $item) {
			if (($item['from'] == $bpWord1 || $item['from'] == $bpWord2) && isset($item['color'])) {
				$item['color'] = 'red';
			}
			if (($item['to'] == $bpWord1 || $item['to'] == $bpWord2) && isset($item['color'])) {
				$item['color'] = 'red';
			}
		}
	}
} else {
	$scores = [0];
}


echo json_encode(['content' => $content, 'nodes' => $nodes, 'words' => $words, 'target' => $target, 'current' => min($scores)]);