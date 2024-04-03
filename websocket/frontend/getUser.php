<?php
include_once "../../class/user.php";

session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    echo json_encode($user->getUsername());
}else{
    echo 'NULL';
}
