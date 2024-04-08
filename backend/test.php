<?php

$id = 2;

// exec("../../libraries/apache-maven-3.9.6/bin/mvn --version 2>&1", $output5);
// echo "<pre>";
// print_r($output5);
// echo "</pre>";

chdir("../c");
exec("gcc executable/build_lex_index.c ./src/*.c -o build_lex_index.out -lm");
exec("gcc executable/new_game.c ./src/*.c -o new_game.out -lm");
exec("gcc executable/add_word.c ./src/*.c -o add_word.out -lm");

exec("./build_lex_index.out dico.bin");

exec("./new_game.out dico.bin $id environnement cool 2>&1", $output);

// "chien environnement 50"
// 


// exec("./add_word.out dico.bin $id mehdi chien 2>&1");
// exec("./add_word.out dico.bin $id mehdi chiens 2>&1");


// print_r($output);

[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

// var_dump($startWord);
// var_dump($endWord);

chdir("../java");
// $java_home="../../libraries/jdk-21.0.2";
// putenv('JAVA_HOME=' . $java_home);
// exec("../../libraries/apache-maven-3.9.6/bin/mvn compile 2>&1", $output);

// echo "<pre>";
// print_r($output);
// echo "</pre>";

exec("../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id");

chdir("../c");
$lines = file('games/' . $id . '-game/msTree.txt');

[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);

$data = []; 

foreach ($lines as $line) {
    [$word1, $word2, $score] = explode(",", $line);
    $data[] = ["from" => $word2, "to" => $word1, "linkFormat" => $score];
    $data[] = ["from" => $word1, "to" => $word2, "linkFormat" => $score];	
}

echo "<pre>";
echo json_encode(["data" => $data]);
echo "</pre>";