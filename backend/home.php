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

?>






<!Doctype HTML>
<html>
    <head>
        <title>Home</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../style/style.css" />
    </head>
    <body>
        <div class="container center space">
            <h1>Accueil
            </h1>
            <h3><u>Mode de jeu disponible :</u></h3>
            <a href="onePlayer.php"><button>1 joueur</button></a>
            <!-- <a href="twoPlayer.php"><button>2 joueurs</button></a> -->
            <a href="disconnect.php"><button>Déconnexion</button></a>
        </div>
        <a href="profile.php">
            
            <div class="profil-container">
                <h2><?php echo $user->getUsername()?></h2>
                <div class="profil center">
                    <div class="profil-head"></div>
                    <div class="profil-body"></div>
                </div>   
            </div>
             
        </a>
    </body>
</html>
