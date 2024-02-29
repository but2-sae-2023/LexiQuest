<?php 

	session_start();
	include_once("../class/user.php");
	include_once("../class/game.php");

	$user = $_SESSION['user'];

	$id = 35;

	if($_SESSION['gameRunning'] == "no") {
		$_SESSION['gameRunning'] = "yes";
		$game = new game();
		// $game->playGame($user->getUserId(), 50);

		chdir("../c");
		exec("./new_game.out dico.bin $id voiture maison appartement chien loup sculpture plante fleur rose eau feu air terre soleil 2>&1");

		[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

		chdir("../java");
		exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id", $output2);
	}

	if (isset($_POST['userWord'])) {
		$userWord = $_POST['userWord'];
		chdir("../c");
		exec("./add_word.out dico.bin $id mehdi $userWord 2>&1", $output3);

		chdir("../java");
		exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id", $output2);
		// exec("output/add_word.out output.lex '$userWord'");

		// chdir("../java");
		// exec("java -cp target/classes fr.uge.Main '$startWord' '$endWord' ../c/gameFile.txt");
	}

	chdir("../c");
	$lines = file('games/'.$id.'-game/msTree.txt');

	[$startWord, $endWord] = array_map('trim', explode(",", file("gameFile.txt")[1]));

	$data = [];
	$nodes = [];
	foreach ($lines as $line) {
		[$word1, $word2, $score] = explode(",", $line);
		array_push($data, ["from" => $word2, "to" => $word1, "linkFormat" => $score]);
		array_push($data, ["from" => $word1, "to" => $word2, "linkFormat" => $score]);
		if ($word1 == $startWord) {
			array_push($nodes, ["id" => $word1, "color" => "green"]);	
		}
		if ($word2 == $endWord) {
			array_push($nodes, ["id" => $word2, "color" => "yellow"]);
		}
	}

	echo json_encode(["data" => $data, "nodes" => $nodes]);
?>