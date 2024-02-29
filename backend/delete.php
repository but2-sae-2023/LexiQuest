<?php

chdir("../c/games");
// exec("ls -l 2>&1", $output);

system("rm -rf *"); 

chdir("../../java");
exec("ls -l 2>&1", $output);
system("rm -r target/");

echo "<pre>";
print_r($output);
echo "</pre>";