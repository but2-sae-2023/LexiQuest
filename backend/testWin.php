<?php

chdir("../c/winOutput");

if (!file_exists("output.lex")) {
    exec("build_lex_index.exe output.bin > 2>&1", $output);
}
exec("gameInit.bat > 2>&1", $output);
system("cmd /c gameInit.bat");
print_r($output);

?>