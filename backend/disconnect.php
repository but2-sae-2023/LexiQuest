<?php
    include_once("../class/user.php");
    session_start();
    if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $user = $user->disconnect($user->getUsername());
        $_SESSION['user'] = $user;
        session_destroy();
        header('location: ../index.php');
    } else {
        session_destroy();
        header('location: ../index.php');
    }  
?>