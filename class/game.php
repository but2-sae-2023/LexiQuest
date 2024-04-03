<?php   
include_once("../class/user.php");
class Game {

    private $game_id;

    public function playGame($user_id) {
        $cnx = User::init_cnx();
        $game_timestamp = time();
        $request = $cnx->prepare("INSERT INTO sae_game (game_timestamp, user_id) VALUES (?, ?);");
        $request->bind_param("ss", $game_timestamp, $user_id);
        return $request->execute(); 
        
    }

    // private function updateUserScore($user_id, $score)
    // {
    //     $cnx = User::init_cnx();
    //     $request = $cnx->prepare("INSERT INTO sae_score (score, score_timestamp, user_id) VALUES (?, ?, ?);");
    //     $request->bind_param("sis", $score, time(), $user_id);
    //     $request->execute();
    // }

    public function updateScore($user_id, $score) {
        $cnx = User::init_cnx();

        $game_id = self::getGameId($user_id);
        $game_timestamp = time();
        $request = $cnx->prepare("INSERT INTO sae_score (score, score_timestamp, game_id) VALUES (?, ?, ?);");
        $request->bind_param("isi", $score, $game_timestamp, $game_id);
        $request->execute();
    }

    public function getGameId($user_id) {
        $cnx = User::init_cnx();
        $request = $cnx->prepare("SELECT game_id FROM sae_game WHERE user_id = ? ORDER BY game_id DESC;");
        $request->bind_param("s", $user_id);
        $request->execute();
        $result = $request->get_result();
        $row = $result->fetch_assoc();
        return $row['game_id'];
    }

    public function openFile() {

    }

}

?>