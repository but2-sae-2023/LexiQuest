<?php
$moteur_scores = "../c";
exec("cd $moteur_scores");


$new_game=glob($moteur_scores."/new_game.*");
$build_lex_index=glob($moteur_scores."/build_lex_index.*");
$add_word=glob($moteur_scores."/add_word.*");

//BUILD new_game
if($new_game==NULL){
    exec("cd $moteur_scores && gcc ./executable/new_game.c ./src/*.c -o new_game.out -lm");
}

//BUILD build_lex_index
if($build_lex_index==NULL){
    exec("cd $moteur_scores && gcc ./executable/build_lex_index.c ./src/*.c -o build_lex_index.out -lm");
}

//BUILD add_word
if($add_word==NULL){
    exec("cd $moteur_scores && gcc ./executable/add_word.c ./src/*.c -o add_word.out -lm");
}

exec("cd $moteur_scores && ./build_lex_index ./dico.bin", $output, $result_code);
var_dump($output);

?>