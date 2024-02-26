
<?php
include_once("../class/user.php");
include_once("../class/game.php");
session_start();
if (isset($_SESSION['backend'])) {
    unset($_SESSION['backend']);
}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if (!$user->checkIfConnected()) {
        header('location: ../index.php');
    }
// Code à rechercher dans la base de données
$privcode = $_POST['code'] ;

// Requête SQL pour rechercher le code dans la base de données
$sql = "SELECT user_id FROM private_parties WHERE privcode = '$code_to_search'";

// Exécution de la requête
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si le code est trouvé, récupérer l'user_id correspondant
    $row = $result->fetch_assoc();
    $user_id = $row["user_id"];
    echo "Code trouvé. L'ID de l'utilisateur est : $user_id";
} else {
    echo "Code non trouvé dans la base de données.";
}

$gameFile = fopen("../C/Games/$user_id-game/gameFile.txt", "r");
$indexes = array();
$indexes = explode(",", fgets($gameFile));
$indexes[0] = intVal($indexes[0]);
$indexes[1] = intVal($indexes[1]);
$nWords = $indexes[0];
$lineCouples = $indexes[1];
$words = fgets($gameFile);
$oldWords = array();
$oldCouples = array();
for ($i = 0; $i < $nWords; $i++) {
    $oldWords[$i] =  fgets($gameFile);
}
$i = 0;
while (!feof($gameFile)) {
    $oldCouples[$i] = fgets($gameFile);
    $i++;
}
unset($couples[$i - 1]);

fclose($gameFile);
$couple = explode(",", $oldCouples[0]);
$couple_lev_score =  intval($couple[3]);
$couple_sem_score = intval($couple[2]);
$linkFormatValue = round(max($couple_lev_score, $couple_sem_score));

$startWord = explode(",", $oldWords[0])[0];
$endWord = explode(",", $oldWords[1])[0];

$data = array(
    array(
        "from" => $startWord,
        "to" => $endWord,
        "color" => 'white',
        "linkFormat" => $linkFormatValue
    ),
    array(
        "from" => $endWord,
        "to" => $startWord,
        "color" => 'white',
        "linkFormat" => $linkFormatValue
    )
);
$_SESSION['data'] = $data;
$_SESSION['verification'] = "yes";

$game->playGame($user_id, $linkFormatValue);
$game->updateScore($user_id, $linkFormatValue);


$conn->close();
?>
