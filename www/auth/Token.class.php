<?php

/**
 * Token.class.php
 * 
 * This class lets you generate tokens and use them freely handling the database interface.
 * @version 20201224
 * @author JimmyBear217
 * 
 * @method string __construct(string $username, string $token='') initialize a Token.
 * if no token provided, it automatically generates a blank one.
 * you will then need to set the access for this token for it to become valid
 * 
 * @method void db_connect() create the object's own connection to the database.
 * @method void generate_token() generates a valid token (verified with DB) and stores it in $this->token
 * @method bool set_access() sets the access of the current token if allowed by $this->is_token_new
 * @method int get_expiration() returns the expiration of the token from cached data
 * @method void load_token() loads the token from database and stores its details in the object's properties
 * @method bool consume() consume the token and invalidates it for any further use. useful for password resets.
 * 
 * @property string $token stores the token that has been generated or retrieved earlier
 * @property string $access stores the access level of the current token
 * @property string $username stores the username linked with the current token
 * @property mixed $token_db stores the unique database connection
 * @property bool $is_token_new keeps track if the token is new or not. this defines what we are allowed to edit.
 * @property int $expiration unix epoch timestamp of the expiration time. after this the token is no longer valid.
 * @property bool $is_valid keeps track if the token valid or not. this is defined by it's details.
 * @property array $details stores the details of the token as loaded from DB. it may be unsafe.
 * 
 */

class Token {

    private $valid_access = array(
        "recover_password",
        "app_access",
        "none",
        "verify_email",
        "account_reactivation"
    );
    private $token = '';
    private $access = "none";
    private $username = '';
    private $token_db = null;
    private $is_token_new = false;
    private $expiration = 0;
    private $is_valid = true;
    private $details = array();


    function __construct($username, $token='') {
        $this->username = filter_var($username, FILTER_SANITIZE_STRING);
        $this->db_connect();
        if ($token == '') {
            $this->is_token_new = true;
            $this->generate_token();
        } else {
            $this->is_token_new = false;
            $this->load_token($token);
        }
    }

    function __destruct() {
        $this->token_db = null;
    }

    // connect to DB
    private function db_connect() {
        try {
            $token_db = new PDO("mysql:host=localhost;dbname=u947544758_day2day;charset=utf8;port=3306","u947544758_day2day","uA&5y17*r1");
        } catch (PDOException $e) {
            error_log("Unable to connect to DB: " . $e->getMessage());
            die("unable to connect to the database");
        }
        $this->token_db = $token_db;
    }


    // load and verify an existing tiken
    private function load_token($token) {
        // query token from DB
        if ($this->token_db == null) {
            $this->db_connect();
        }
        $stm = $this->token_db->prepare("SELECT * FROM tokens WHERE token = ?");
        try {
            $stm->execute(array($token));
            $details = $stm->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->is_valid = false;
            error_log("Unable to query token $token: " . $e->getMessage());
            die("unable to verify your token");
        }
        if ($details == false) {
            $this->is_valid = false;
            return false;
        } else {
            $this->details = $details;
        }
        // verify expiration
        if (isset($details["expiration"])) {
            $details["expiration"] = intval($details["expiration"]);
            $time = intval(time());
            if ($time < $details["expiration"]) {
                $this->expiration = $details["expiration"];
            } else {
                $this->expiration = ($details["expiration"] - $time);
                $this->is_valid = false;
            }
        }
        // verify access
        if (isset($details["access"])) {
            $details["access"] = filter_var($details["access"], FILTER_SANITIZE_STRING);
            if (in_array($details["access"], $this->valid_access)) {
                $this->access = $details["access"];
            } else {
                $this->access = "none";
                $this->is_valid = false;
            }
        }
        // verify username
        if (isset($details["username"])) {
            $details["username"] = filter_var($details["username"], FILTER_SANITIZE_STRING);
            if ($details["username"] != $this->username) {
                $this->is_valid = false;
                error_log("Username mismatch on $token: token belongs to " . $details["username"] . " while the current user is " . $this->username);
                die("unable to verify your token");
            }
        }
        // verify token
        if (isset($details["token"])) {
            $details["token"] = filter_var($details["token"], FILTER_SANITIZE_STRING);
            if ($details["token"] != $token) {
                $this->is_valid = false;
                error_log("Token mismatch on query: $token != " . $details["token"]);
                die("unable to verify your token");
            } else {
                $this->token = $token;
            }
        }

    }

    // create a new token
    private function generate_token() {
        // connect to DB
        if ($this->token_db == null) {
            $this->db_connect();
        }
        $stmS = $this->token_db->prepare("SELECT count(token) FROM tokens WHERE token = ?");
        $stmI = $this->token_db->prepare("INSERT INTO tokens(token, username, access, expiration) VALUES(?, ?, ?, ?);");
        // do while loop
        $validNewToken = false;
        $readFail=0;
        try {
            do {
            // generate random token
            $token = bin2hex( random_bytes(32) ); 

            // verify if exists, if so loop again
                $stmS->execute(array($token));
                $result = $stmS->fetch(PDO::FETCH_NUM);
                if (isset($result[0])) {
                    if ($result[0] == 0 || $readFail>=5) {
                        $validNewToken = true;
                    }
                } else {
                    error_log("unable to lookup existing tokens for generation");
                    $readFail++;
                }
            } while (!$validNewToken);
        } catch (PDOException $e) {
            error_log("unable to verify existance of token: " . $e->getMessage());
        }
        // insert token with "none" access
        $expiration = intval(time()+(60*60*24));
        try {
            $result = $stmI->execute(array(
                $token,
                $this->username,
                'none',
                $expiration
            ));
        } catch (PDOException $e) {
            error_log("unable to insert token into DB: " . $e->getMessage());
            die("Unable to generate token.");
        }
        // on success store token in $this->token
        if ($result) {
            $this->token = $token;
            $this->access = 'none';
            $this->expiration = $expiration;
        }
    }

    // use and expire an existing token
    public function consume() {
        if ($this->token_db == null) {
            $this->db_connect();
        }
        $stm = $this->token_db->prepare("DELETE FROM tokens WHERE token = ?");
        try {
            $stm->execute(array($this->token));
            $this->is_valid = false;
        } catch (PDOException $e) {
            error_log("Unable to delete token $this->token: " . $e->getMessage());
        }
    }

    // setters
    public function setAccess($access) {
        if (!in_array($access, $this->valid_access) || !$this->is_token_new) {
            return false;
        }
        if ($this->token_db == null) {
            $this->db_connect();
        }
        $stm = $this->token_db->prepare("UPDATE tokens SET access = ? WHERE token = ?");
        try {
            if($stm->execute(array($access, $this->token))) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Unable to update token $this->token: " . $e->getMessage());
            return false;
        }
    }

    // getters
    public function getAccess() {
        return $this->access;
    }
    public function getExpiration() {
        return $this->expiration;
    }
    public function isValid() {
        return $this->is_valid;
    }
    public function getToken() {
        return $this->token;
    }
}