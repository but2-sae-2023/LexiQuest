<?php 

// echo get_current_user(); echo "<br>";
// echo getcwd();

// exec("../../libraries/apache-maven-3.9.6/bin/mvn -v 2>&1", $output);
// echo "<pre>";
// print_r($output);
// echo "</pre>";

// exec("gcc --version", $output1);
// echo "<pre>";
// print_r($output1);
// echo "</pre>";

// exec("../../libraries/jdk-21.0.2/bin/java --version", $output2);
// echo "<pre>";
// print_r($output2);
// echo "</pre>";

$id = 31;

chdir("../c");
// exec("gcc executable/build_lex_index.c ./src/*.c -o build_lex_index.out -lm");
// exec("gcc executable/new_game.c ./src/*.c -o new_game.out -lm");
// exec("gcc executable/add_word.c ./src/*.c -o add_word.out -lm");

// exec("./build_lex_index.out dico.bin");
// if (!file_exists("gameFile.txt")) {
	exec("./new_game.out dico.bin $id voiture maison appartement chien loup sculpture plante fleur rose eau feu air terre soleil 2>&1");
	// On doit mettre les deux en paramètre
// }

exec("./add_word.out dico.bin $id mehdi singe 2>&1", $output3);

// $lines = file('gameFile.txt');
// $startWord = explode(",", $lines[1])[0];
// $endWord = explode(",", $lines[1])[1];

[$startWord, $endWord] = explode(",", file("games/$id-game/gameFile.txt")[1]);
echo $startWord; 

// echo $startWord . ", " . $endWord;

// $java_home="../../libraries/jdk-21.0.2";
// putenv('JAVA_HOME=' . $java_home);

chdir("../java");
// exec("../../libraries/apache-maven-3.9.6/bin/mvn compile 2>&1", $output);
echo "<pre>";
print_r($output);
echo "</pre>";
$commande = "../../libraries/jdk-21.0.2/bin/java -cp target/classes fr.uge.Main '$startWord' '$endWord' $id 2>&1";
// echo $commande;
exec($commande, $output2);
echo "<pre>";
print_r($output2);
echo "</pre>"; 

print_r($output3);
?>
