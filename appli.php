<?php

// Inclure la classe utilisateur
include_once ("class/user.php");
session_start();
$_SESSION['backend'] = false;

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']->checkIfConnected()) {
        header('location: ./frontend/home.php');
    }
}

// Définir le tableau de réponse
$response = array();

// Vérifier si les données de connexion sont envoyées via POST
if (isset($_GET['user']) && isset($_GET['pwd'])) {
    $mdp = $_GET['user'];
    $user = new User();
    $user = $user->connect($_GET['user'], $_GET['pwd']);

    if ($user) {
        // Connexion réussie
        $_SESSION['user'] = $user;
        $response['success'] = true;
        $response['message'] = "Youpi Réussi";
        header('location: ./frontend/home.php');

    } else {
        // Échec de la connexion
        $response['success'] = false;
        $response['message'] = $mdp;
    }

} else {
    // Si les données ne sont pas envoyées via POST, renvoyer une erreur
    $response['success'] = false;
    $response['message'] = "Les données de connexion sont manquantes";
}

// Renvoyer la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);

?>