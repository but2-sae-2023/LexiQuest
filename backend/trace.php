<?php
include_once ("../class/user.php");
include_once ("../private/cnx.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    }
} else {
    header('location: ../index.php');
}

// Requête SQL pour récupérer les 500 dernières traces
$sql = "SELECT t.date, t.hour, t.ip, t.user_id, t.action, u.username
        FROM sae_trace t
        JOIN sae_user u ON t.user_id = u.user_id
        ORDER BY t.trace_id DESC
        LIMIT 500";

$result = $cnx->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trace</title>
    <link rel="stylesheet" href="../style/style.scss">
    <link rel="stylesheet" href="../style/trace.scss">
</head>

<body>
    <div class="container">
        <table>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>IP</th>
                <th>ID Utilisateur</th>
                <th>Nom d'utilisateur</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['date']}</td>
                            <td>{$row['hour']}</td>
                            <td>{$row['ip']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['action']}</td>
                          </tr>";
                }
                $result->free();
            }
            $cnx->close();
            ?>
        </table>
    </div>
    <a href="../frontend/home.php">
        <div class="back"></div>
    </a>
</body>

</html>