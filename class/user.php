<?php

class User
{
    private $user_id;
    private $username;
    private $password;
    private $email;
    private $birth_year;
    private $date_last_cnx;
    private $date_signup;
    private $active;
    private $connected;

    private $token;
    private $token_expiration;

    private $nb_game_played;
    private $avg_score;
    private $min_score;
    private $max_score;

    // Auxiliary functions

    public static function init_cnx()
    {
        if (isset($_SESSION['backend'])) {
            return include './private/cnx.php';
        }
        return include '../private/cnx.php';
    }

    // Signup functions

    public function signup($username, $password, $email, $birth_year)
    {
        if ((strlen($username) < 3 || strlen($username) > 24) && is_string($username)) {
            return "username_error";
        }
        if ((strlen($password) < 12 || strlen($password) > 32) && is_string($password)) {
            return "password_error";
        }
        $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!filter_var($email, FILTER_SANITIZE_EMAIL) || strlen($email) > 50 || !preg_match($emailRegex, $email)) {
            return "email_error";
        }
        $this->username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $this->password = hash('sha256', $password);
        $this->email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $this->birth_year = $birth_year;

        include_once("mail.php");

        if (self::checkAccount()) {
            echo "check valide\n";
            if (self::insertUser()) {
                echo "insert valide\n";
                if (self::insertToken()) {
                    echo "token valide\n";
                    if (self::sendConfirmationEmail()) {
                        echo "mail envoyé\n";
                        return true;
                    } else {
                        "mail non envoyé";
                        return false;
                    }
                } else {
                    echo "token invalide";
                    return false;
                }
            } else {
                echo "insert invalide";
                return false;
            }
        } else {
            echo "check invalide";
            return false;
        }
    }

    private function checkAccount($username = null, $email = null) {
        $cnx = self::init_cnx();
        if ($username == null) {
            $username = $this->username;
        }
        if ($email == null) {
            $email = $this->email;
        }
        $request = $cnx->prepare("SELECT * FROM sae_user WHERE username = ? OR email = ?");
        $request->bind_param("ss", $username, $email);
        $request->execute();
        
        $result = $request->get_result();
        return $result->num_rows <= 0;
    }

    private function insertUser()
    {
        $cnx = self::init_cnx();
        $this->date_signup = date("Y-m-d");
        $this->connected = 0;
        $request = $cnx->prepare("INSERT INTO sae_user (username, password, email, birth_year, date_signup, date_last_cnx, active, connected) VALUES (?, ?, ?, ?, ?, NULL, 0, 0);");
        $request->bind_param("sssss", $this->username, $this->password, $this->email, $this->birth_year, $this->date_signup);
        return $request->execute();
    }

    private function insertToken()
    {
        $cnx = self::init_cnx();
        $this->token = bin2hex(random_bytes(32));
        $this->token_expiration = date("Y-m-d", strtotime(date("Y-m-d") . '+ 1 day'));
        $user_id = self::getUserId();
        $request = $cnx->prepare("INSERT INTO sae_token (token, date_expiration, user_id) VALUES (?, ?, ?);");
        $request->bind_param("ssi", $this->token, $this->token_expiration, $user_id);
        return $request->execute();
    }

    private function sendConfirmationEmail()
    {
        $mail = new Mail($this->email, "confirm");
        if ($this->token == null || $this->token == "") {
            self::insertToken();
        }
        return $mail->send($this);
    }

    // signup validation with token

    public function checkToken($token, $checkType, $newPwd = null)
    {
        $cnx = self::init_cnx();
        $request = $cnx->prepare("SELECT user_id FROM sae_token WHERE token = ? AND CURDATE() <= date_expiration");
        $request->bind_param("s", $token);
        $request->execute();
        $result = $request->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];

            if ($checkType == "signup") {
                $request = $cnx->prepare("UPDATE sae_user SET active = 1 WHERE user_id = ?");
                $request->bind_param("s", $user_id);
                $request->execute();
            }

            if ($checkType == "reset") {
                $newPwd = hash('sha256', $newPwd);
                $request = $cnx->prepare("UPDATE sae_user SET password = ? WHERE user_id = ?");
                $request->bind_param("ss", $newPwd, $user_id);
                $request->execute();
            }
            
            $request = $cnx->prepare("DELETE FROM sae_token WHERE token = ?");
            $request->bind_param("s", $token);
            $request->execute();

            if ($checkType == "signup") {
                return "success_signup";
            } else {
                return "success_reset";
            }
        }
    }

    // filled fields with db informations
    private function constructUserFromDb($user_id)
    {
        $cnx = self::init_cnx();
        $request = $cnx->prepare("SELECT * FROM sae_user WHERE user_id = ?");
        $request->bind_param("s", $user_id);
        $request->execute();
        $result = $request->get_result();
        $row = $result->fetch_assoc();
        $user = new User();
        $user->setFields($row['user_id'], $row['username'], $row['password'], $row['email'], $row['birth_year'], $row['date_signup'], $row['date_last_cnx'], $row['active'], $row['connected']);
        return $user;
    }

    // username et email unique
    // connect function with verification and updating
    public function connect($username, $password)
    {
        if (self::checkIfRegistered($username, $password)) {
            $user_id = self::getUserId($username);
            self::updateConnectedStatus($user_id, true);
            $user = self::constructUserFromDb($user_id);
            if ($user->getActive()) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function disconnect($username)
    {
        $user_id = self::getUserId($username);
        self::updateConnectedStatus($user_id, false);
        return self::constructUserFromDb($user_id);
    }

    private function checkIfRegistered($username, $password)
    {
        $cnx = self::init_cnx();
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $password = hash('sha256', $password);
        $request = $cnx->prepare("SELECT username, password FROM sae_user WHERE username = ? AND password = ?");
        $request->bind_param("ss", $username, $password);
        $request->execute();
        $result = $request->get_result();
        return $result->num_rows > 0;
    }

    public function checkIfConnected()
    {
        return $this->connected;
    }

    public function updateUser($username, $email, $birth_year, $password)
    {
        $cnx = self::init_cnx();
        if (self::checkUsername($username) && $username != $this->username) {
            echo "<h2 class='red'> Nom d'utilisateur invalide </h2> ";
            return $this;
        }

        if (self::checkEmail($email) && $email != $this->email) {
            echo "<h2 class='red'> Adresse Email invalide invalide </h2> ";
            return $this;
        }

        if (!self::checkIfRegistered($this->username, $password)) {
            echo "<h2 class='red'> Mot de passe invalide </h2> ";
            return $this;
        }
        $request = $cnx->prepare("UPDATE sae_user SET username = ?, email = ?, birth_year = ? WHERE user_id = ?");
        $request->bind_param("ssss", $username, $email, $birth_year, $this->user_id);
        $request->execute();
        return self::constructUserFromDb($this->user_id);
    }

    private function checkUsername($username) {
        $cnx = self::init_cnx();
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $request = $cnx->prepare("SELECT username FROM sae_user WHERE username = ?");
        $request->bind_param("s", $username);
        $request->execute();
        $result = $request->get_result();
        return $result->num_rows > 0;
    }

    private function checkEmail($email) {
        $cnx = self::init_cnx();
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $request = $cnx->prepare("SELECT email FROM sae_user WHERE email = ?");
        $request->bind_param("s", $email);
        $request->execute();
        $result = $request->get_result();
        return $result->num_rows > 0;
    }

    // Reset password functions
    public function forgotPwd($username, $email) {
        if (!self::checkAccount($username, $email)) {
            include_once("mail.php");
            $mail = new Mail($email, "reset");
            $user = self::constructUserFromDb(self::getUserId($username));
            self::setFields($user->getUserId(), $user->getUsername(), $user->getPassword(), $user->getEmail(), $user->getBirthYear(), $user->getSignupDate(), $user->getDateLastCnx(), $user->getActive(), $user->getConnected());
            self::insertToken();
            return $mail->send($this);
        }
    }

    // Getters

    public function getUserId($username = null)
    {
        $cnx = self::init_cnx();
        if ($username == null) {
            $username = $this->username;
        }
        $request = $cnx->prepare("SELECT user_id FROM sae_user WHERE username = ?;");
        $request->bind_param("s", $username);
        $request->execute();
        $result = $request->get_result();
        $row = $result->fetch_assoc();
        return $row['user_id'];
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getBirthYear()
    {
        return $this->birth_year;
    }

    public function getSignupDate()
    {
        return $this->date_signup;
    }

    private function getActive()
    {
        return $this->active;
    }

    private function getConnected()
    {
        return $this->connected;
    }

    public function getNumberGamesPlayed($user_id)
    {
        $cnx = self::init_cnx();
        $request = $cnx->prepare("SELECT COUNT(*) FROM sae_score WHERE user_id = ?;");
        $request->bind_param("s", $user_id);
        $request->execute();
        $result = $request->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'];
    }

    public function getScore($user_id, $type)
    {
        $cnx = self::init_cnx();
        $request = $cnx->prepare("SELECT $type(score) as score FROM sae_score WHERE user_id = ?;");
        $request->bind_param("s", $user_id);
        $request->execute();
        $result = $request->get_result();
        $row = $result->fetch_assoc();
        return $row['score'];
    }

    public function getToken()
    {
        return $this->token;
    }

    private function getDateLastCnx()
    {
        return $this->date_last_cnx;
    }

    private function getPassword()
    {
        return $this->password;
    }


    // Setters

    private function setFields($user_id, $username, $password, $email, $birth_year, $date_signup, $date_last_cnx, $active, $connected)
    {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->birth_year = $birth_year;
        $this->date_signup = $date_signup;
        $this->date_last_cnx = $date_last_cnx;
        $this->active = $active;
        $this->connected = $connected;
    }

    private function updateConnectedStatus($user_id, $value)
    {
        $cnx = self::init_cnx();
        $request = $cnx->prepare("UPDATE sae_user SET connected = ? WHERE user_id = ?");
        $request->bind_param("is", $value, $user_id);
        $request->execute();
        if ($value == 1) {
            $request = $cnx->prepare("UPDATE sae_user SET date_last_cnx = ? WHERE user_id = ?");
            $request->bind_param("ss", date("Y-m-d"), $user_id);
            $request->execute();
        }
    }

    public function setStats($user_id) {
        // Récupérer le nombre de parties jouées
        $this->nb_game_played = $this->getNumberGamesPlayed($user_id);
        // Récupérer le score moyen
        $this->avg_score = $this->getScore($user_id, 'AVG');
        // Récupérer le score minimum
        $this->min_score = $this->getScore($user_id, 'MIN');
        // Récupérer le score maximum
        $this->max_score = $this->getScore($user_id, 'MAX');
    }
}
