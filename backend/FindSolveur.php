<?php

// Fonction pour lire le fichier et extraire les données sur les liens entre les mots
function readTreeFile($filename) {
    $links = array();

    $file = fopen($filename, "r");
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $data = explode(",", $line);
            $links[] = array(
                'offset_word1' => trim($data[0]),
                'offset_word2' => trim($data[1]),
                'distance' => trim($data[2])
            );
        }

        fclose($file);
    }

    return $links;
}

// Fonction pour trouver le meilleur chemin entre deux mots
function findBestPath($links, $startWord, $endWord) {
    $bestPath = array();
    $minDistance = INF;

    foreach ($links as $link) {
        if ($link['offset_word1'] == $startWord || $link['offset_word2'] == $startWord) {
            if ($link['offset_word1'] == $endWord || $link['offset_word2'] == $endWord) {
                if ($link['distance'] < $minDistance) {
                    $bestPath = array($link['offset_word1'], $link['offset_word2']);
                    $minDistance = $link['distance'];
                }
            }
        }
    }

    return $bestPath;
}

// Vérification des arguments d'entrée
if ($argc < 4) {
    echo "Usage: php script.php tree_file.txt start_word end_word\n";
    exit(1);
}

// Lecture du fichier tree.txt
$treeFilename = $argv[1];
$links = readTreeFile($treeFilename);

// Mots de départ et d'arrivée fournis par l'utilisateur
$startWord = $argv[2];
$endWord = $argv[3];

// Recherche du meilleur chemin entre les deux mots
$bestPath = findBestPath($links, $startWord, $endWord);

// Affichage du résultat
if (empty($bestPath)) {
    echo "Aucun chemin trouvé entre les mots '$startWord' et '$endWord'.\n";
} else {
    echo "Meilleur chemin entre '$startWord' et '$endWord': " . implode(" -> ", $bestPath) . "\n";
}
