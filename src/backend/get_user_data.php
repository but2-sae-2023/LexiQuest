<?php
session_start();

// Inclure la classe User si elle n'est pas déjà incluse
include_once '../class/user.php';

// Collectez toutes les données nécessaires
$userData = $_SESSION['user'];

// Convertissez le tableau en JSON et renvoyez-le
header('Content-Type: application/json');
echo json_encode(get_object_vars($userData));
?>