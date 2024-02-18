<?php
session_start();
// Collectez toutes les données nécessaires
$userData = $_SESSION['user'];

// Convertissez le tableau en JSON et renvoyez-le
header('Content-Type: application/json');
echo json_encode($userData);
?>