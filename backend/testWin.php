<?php

chdir("../c/winOutput");

system("gcc ../build_lex_index.c -o build_lex_index.exe");
system("gcc ../new_game.c -o new_game.exe");
system("gcc ../add_word.c -o add_word.exe");

if (!file_exists("output.lex")) {
    exec("build_lex_index.exe ../output.bin ", $output);
    print_r($output);
}

exec("new_game.exe output.lex chien chat poisson oiseau lapin cheval vache cochon mouton lion tigre ours elephant girafe singe kangourou renard loup", $output2);
print_r($output2);

echo "<br>";
?>