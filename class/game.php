<?php   
include_once("../class/user.php");
class Game {

    private $game_id;

    public function playGame($user_id, $score) {
        $cnx = User::init_cnx();
        $request = $cnx->prepare("INSERT INTO sae_game (game_timestamp, user_id, score) VALUES (?, ?, ?);");
        $request->bind_param("ssi", time(), $user_id, $score);
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
        $request = $cnx->prepare("INSERT INTO sae_score (score, score_timestamp, game_id) VALUES (?, ?, ?);");
        $request->bind_param("isi", $score, time(), $game_id);
        $request->execute();
    }

    private function getGameId($user_id) {
        $cnx = User::init_cnx();
        $request = $cnx->prepare("SELECT game_id FROM sae_game WHERE user_id = ?;");
        $request->bind_param("s", $user_id);
        $request->execute();
        $result = $request->get_result();
        $row = $result->fetch_assoc();
        return $row['game_id'];
    }

    private function initializeGame($user_id) {
        
    }

}

?>