<?php 

	include_once("../class/user.php");
	include_once("../class/game.php");
	session_start();

	$user = $_SESSION['user'];
	$username = $user->getUsername();

	$user_id = $user->getUserId();
	if (isset($_SESSION['gameId'])) {
		$id = $_SESSION['gameId'];
	}

	if($_SESSION['gameRunning'] == "no") {
		$_SESSION['gameRunning'] = "yes";
		$game = new game();
		// var_dump($user);	
		$game->playGame($user_id);
		$id = $game->getGameId($user_id);
		$_SESSION['gameId'] = $id;
		chdir("../c");
		exec("./new_game.out dico.bin $id voiture maison appartement chien loup plante fleur eau feu air terre 2>&1");

		[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

		chdir("../java");
		exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");

		$nodes = [];
		array_push($nodes, ["id" => $startWord, "color" => "#567fe8"]);
		array_push($nodes, ["id" => trim($endWord), "color" => "#e8a956"]);

		$_SESSION['nodes'] = $nodes;
	}
	if (isset($_POST['userWord'])) {
		$id = $_SESSION['gameId'];
		$userWord = $_POST['userWord'];
		chdir("../c");
		exec("./add_word.out dico.bin $id $username $userWord");

		chdir("../java");
		exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");
	}

	chdir("../c");
	$lines = file('games/'.$id.'-game/msTree.txt');

	[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

	$data = [];
	$nodes = $_SESSION['nodes'];
	foreach ($lines as $line) {
		[$word1, $word2, $score] = explode(",", $line);
		array_push($data, ["from" => $word2, "to" => $word1, "linkFormat" => $score]);
		array_push($data, ["from" => $word1, "to" => $word2, "linkFormat" => $score]);	
		array_push($nodes, ["id" => $word1, "color" => "white"]);
		array_push($nodes, ["id" => $word2, "color" => "white"]);
	}

	echo json_encode(["data" => $data, "nodes" => $nodes]);