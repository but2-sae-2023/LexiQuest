<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once ("../class/user.php");
session_start();
if (isset($_SESSION['backend'])) { unset($_SESSION['backend']); }

if (isset($_POST['user']) && isset($_POST['pwd'])) {
    $user = new User();
    $user = $user->connect($_POST['user'], $_POST['pwd']);
    if ($user) {
        $_SESSION['user'] = $user;
        echo 'login_ok';
    } else {
        echo "pas bon";
    }
}// if (isset($_POST['user']) && isset($_POST['pwd'])) {
//     $user = new User();
//     $user = $user->connect($_POST['user'], $_POST['pwd']);
//     if ($user) {
//         echo 'login_ok';
//     }
// }
