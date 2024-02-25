<?php 
	session_start();

	// $inputWord = $_POST['user_word'];
	// echo $inputWord;

	if($_SESSION['gameRunning'] == "no") {
		$_SESSION['gameRunning'] = "yes";

		if (file_exists("../java/msTree.txt")) {
			exec("rm ../java/msTree.txt");
		}

		chdir("../c");
		exec("output/new_game.out output.lex voiture maison immeuble appartement chien loup sculpture plante fleur rose eau feu air terre soleil");
		[$startWord, $endWord] = explode(",", file("gameFile.txt")[1]);

		chdir("../java");
		exec("java -cp target/classes fr.uge.Main '$startWord' '$endWord' ../c/gameFile.txt");
	}

	if (isset($_POST['userWord'])) {
		$userWord = $_POST['userWord'];
		chdir("../c");
		exec("output/add_word.out output.lex '$userWord'");

		chdir("../java");
		exec("java -cp target/classes fr.uge.Main '$startWord' '$endWord' ../c/gameFile.txt");
	}

	chdir("../java");
	$lines = file('msTree.txt');

	chdir("../c");
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