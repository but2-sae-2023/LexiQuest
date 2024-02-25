<?php 

// exec("mvn --version", $output);
// echo "<pre>";
// print_r($output);
// echo "</pre>";

// exec("gcc --version", $output1);
// echo "<pre>";
// print_r($output1);
// echo "</pre>";

// exec("java --version", $output2);
// echo "<pre>";
// print_r($output2);
// echo "</pre>";

chdir("../c");
exec("gcc build_lex_index.c -o ./output/build_lex_index.out -lm");
exec("gcc new_game.c -o ./output/new_game.out -lm");
exec("gcc add_word.c -o ./output/add_word.out -lm");

exec("output/build_lex_index.out output.bin");
// if (!file_exists("gameFile.txt")) {
	exec("output/new_game.out output.lex voiture maison immeuble appartement chien loup sculpture plante fleur rose eau feu air terre soleil");
// }

exec("output/add_word.out output.lex singe");

// $lines = file('gameFile.txt');
// $startWord = explode(",", $lines[1])[0];
// $endWord = explode(",", $lines[1])[1];

[$startWord, $endWord] = explode(",", file("gameFile.txt")[1]);

echo $startWord . ", " . $endWord;

chdir("../java");
exec("mvn compile 2>&1", $output);
echo "<pre>";
print_r($output);
echo "</pre>";
$commande = "java -cp target/classes fr.uge.Main '$startWord' '$endWord' ../c/gameFile.txt 2>&1";
// echo $commande;
exec($commande, $output2);
echo "<pre>";
print_r($output2);
echo "</pre>";
?>
