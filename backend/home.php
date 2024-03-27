<?php
    include_once("../class/user.php");
    session_start();
    if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }
    $_SESSION['gameRunning'] = "no";
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        if (!$user->checkIfConnected()) {
            header('location: ../index.php');
        } else {
            echo json_encode($user->getUsername());
        }
    } else {
        header('location: ../index.php');
    }F
?>