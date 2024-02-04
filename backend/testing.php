<?php

// print_r("Execution de build_lex : ");
// echo "<br>";
// exec("gcc ../LexiQuest/build_lex_index.c -o ./out/build_lex_index.out -lm 2>&1", $output);
// echo exec("../LexiQuest/out/build_lex_index.out ../LexiQuest/fichier.bin 2>&1");
// print_r($output);
// echo "<br>";

// Change to the LexiQuest directory
chdir('../LexiQuest/');

// Compile the build_lex_index.c file
$command = "gcc ./build_lex_index.c -o build_lex_index.out -lm 2>&1";
exec($command, $output, $return_var);
// echo "Compilation output: " . print_r($output, true) . "<br>";

// Check if the compilation was successful
if ($return_var == 0)
{
    // Get the modification times of the .c file and the output.lex file
    $c_time = filemtime('build_lex_index.c');
    $lex_time = filemtime('output.lex');

    // Check if the output.lex file is older than the .c file
    if ($lex_time < $c_time)
    {
        // echo "Output.lex file is outdated, rebuilding index...";

        // Execute the build_lex_index.out file
        $command = "./build_lex_index.out fichier.bin 2>&1";
        exec($command, $output, $return_var);
        // echo "Execution output: " . print_r($output, true) . "<br>";
    }
}

print_r("Execution de new_game : \n");
echo "<br>";
exec("cd ../LexiQuest/ && gcc new_game.c -o new_game.out -lm 2>&1");
exec("cd ../LexiQuest/ && ./new_game.out output.lex chien chat camion avion transport église gâteau place voiture 2>&1", $output);
print_r($output);
echo "<br>";
exec("cd ../LexiQuest/ && gcc add_word.c -o add_word.out -lm 2>&1", $output1);
// print_r($output1);
echo "<br>";
exec("cd ../LexiQuest/ && ./add_word.out output.lex maison 2>&1", $output2);
// print_r($output2);
echo "<br>";

// print_r("Execution de add_word.c : ");
// exec("gcc ../LexiQuest/add_word.c -o ./out/add_word.out -lm");
// echo exec("../LexiQuest/out/add_word.out 2>&1");
// print_r($output);
?>
