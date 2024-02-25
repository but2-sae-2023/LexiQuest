<?php

/*
 * création d'objet PDO de la connexion qui sera représenté par la variable $cnx
 */
$servername = "sqletud.u-pem.fr";
$username = "xxx";
$password = "xxx";
$dbname = "xxx";

$cnx = new mysqli($servername, $username, $password, $dbname);

mysqli_set_charset($cnx, "utf8");

if ($cnx->connect_error) {
    die("Connection failed: " . $cnx->connect_error);
} else {
    return $cnx;
}

// Remplacer user et pass par "XXX" cnx.php.backup
// vidé valeur essentiel (expurger)
// durée de token à ajouté

 /* Utiliser l'instruction suivante pour afficher le détail de erreur sur la
 * page html. Attention c'est utile pour débugger mais cela affiche des
 * informations potentiellement confidentielles donc éviter de le faire pour un
 * site en production.*/
//    echo "Error: " . $e;


?>
