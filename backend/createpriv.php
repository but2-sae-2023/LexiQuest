<?php
    include_once("../class/user.php");
    session_start();
    if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }
    if (isset($_SESSION['verification'])) { unset($_SESSION['verification']); }
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        if (!$user->checkIfConnected()) {
            header('location: ../index.php');
        }
    } else {
        header('location: ../index.php');
    }


if (chdir("../java/")) {                

} else {
    echo "Erreur : Impossible de changer de répertoire.\n";
}





$fichier = fopen("gameFile.txt", 'r');

if ($fichier) {
    fgets($fichier);
    fgets($fichier);

    $mot_dep = fgets($fichier);
    $mot_depart = explode(',', $mot_dep)[0];

   

    
    $mot_arr = fgets($fichier);
    $mot_arrive = explode(',', $mot_arr)[0];

    
    fclose($fichier);

    
    echo "Mot de depart  : $mot_depart<br>";
    echo "Mot d'arrive  : $mot_arrive<br>";
} else {
    echo "Impossible d'ouvrir le fichier.";
}


exec("../maven/bin/mvn compile 2>&1 " , $output, $returnn);



print_r($output);
echo " $output <br>";
echo " $returnn <br>";


$user_id = $user->getUserId();
$name =  $user->getUsername();


    $commandeMaven = "../jdk/usr/lib/jvm/jdk/bin/java -cp ./target/classes fr.uge.Main $mot_depart $mot_arrive $user_id 2>&1";

    exec($commandeMaven, $outputMaven, $returnMaven);
              



$fichier = fopen("msTree.txt", "r");


if ($fichier) {

    while (($ligne = fgets($fichier)) !== false) {
     
        $ligne = trim($ligne);
        
  
        $champs = str_getcsv($ligne, ",");
        
     
        echo "1 : " . $champs[0] . ", 2 : " . $champs[1] . ", Nombre : " . $champs[2] . "\n";
    }
    
 
    fclose($fichier);
} else {
  
    echo "Erreur : Impossible d'ouvrir le fichier.";
}



// si il créer une partie privée  

// Générer un code aléatoire à 6 chiffres
function generateRandomCode() {
    $code = "";
    for ($i = 0; $i < 6; $i++) {
        $code .= mt_rand(0, 9); // Utilisation de mt_rand() pour une meilleure performance
    }
    return $code;
}

// Utilisation de la fonction pour générer un code aléatoire
$code = generateRandomCode();
echo "Code de partie : " . $random_code;






// Requête SQL pour insérer les valeurs dans la table
$sql = "INSERT INTO private_parties (user, user_id, code) VALUES ('$name', $user_id, '$code')";

// Exécution de la requête
if ($conn->query($sql) === TRUE) {
    echo "Enregistrement inséré avec succès";
} else {
    echo "Erreur lors de l'insertion de l'enregistrement : " . $conn->error;
}

$gameFile = fopen("../C/Games/$user_id-game/gameFile.txt", "r");
$indexes = array();
$indexes = explode(",", fgets($gameFile));
$indexes[0] = intVal($indexes[0]);
$indexes[1] = intVal($indexes[1]);
$nWords = $indexes[0];
$lineCouples = $indexes[1];
$words = fgets($gameFile);
$oldWords = array();
$oldCouples = array();
for ($i = 0; $i < $nWords; $i++) {
    $oldWords[$i] =  fgets($gameFile);
}
$i = 0;
while (!feof($gameFile)) {
    $oldCouples[$i] = fgets($gameFile);
    $i++;
}
unset($couples[$i - 1]);

fclose($gameFile);
$couple = explode(",", $oldCouples[0]);
$couple_lev_score =  intval($couple[3]);
$couple_sem_score = intval($couple[2]);
$linkFormatValue = round(max($couple_lev_score, $couple_sem_score));

$startWord = explode(",", $oldWords[0])[0];
$endWord = explode(",", $oldWords[1])[0];

$data = array(
    array(
        "from" => $startWord,
        "to" => $endWord,
        "color" => 'white',
        "linkFormat" => $linkFormatValue
    ),
    array(
        "from" => $endWord,
        "to" => $startWord,
        "color" => 'white',
        "linkFormat" => $linkFormatValue
    )
);
$_SESSION['data'] = $data;
$_SESSION['verification'] = "yes";

$game->playGame($user_id, $linkFormatValue);
$game->updateScore($user_id, $linkFormatValue);

?>







<style>
    #container {
    min-width: 320px;
    max-width: 800px;
    margin: 0 auto;
    height: 500px;
}
</style>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/networkgraph.js"></script>
<div id="container"></div>
<script>
    Highcharts.chart('container', {
    chart: {
        type: 'networkgraph'
    },
    plotOptions: {
        networkgraph: {
            draggable: false,
            layoutAlgorithm: {
                enableSimulation: true
            }
        }
    },

    series: [{
        dataLabels: {
            enabled: true,
            linkTextPath: {
                attributes: {
                    dy: 12
                }
            },
            linkFormat: '{point.fromNode.name} \u2192 {point.toNode.name}',
            textPath: {
                enabled: true,
                attributes: {
                    dy: 14,
                    startOffset: '45%',
                    textLength: 80
                }
            },
            format: 'Node: {point.name}'
        },
        marker: {
            radius: 35
        },
        data: [{
            from: 'n1',
            to: 'n2'
        }, {
            from: 'n2',
            to: 'n3'
        }, {
            from: 'n3',
            to: 'n4'
        }, {
            from: 'n4',
            to: 'n5'
        }, {
            from: 'n5',
            to: 'n1'
        }]
    }]
});
</script>
