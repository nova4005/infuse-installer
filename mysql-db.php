<?php

class MySQL_DB
{
    public function __construct($databaseName, $username, $password)
    {
        $this->db = $databaseName;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect()
    {
        return mysqli_connect('localhost', $this->username, $this->password, $this->db);
    }

    public function retrieve()
    {
        $link = $this->connect();

        $query = "SELECT is_access_token FROM `admin_tokens` WHERE ID = 1 LIMIT 1";

        $stmt = $link->prepare($query);

        if ($stmt) {
            $stmt->execute();

            $result = $stmt->get_result();

            $stmt->close();

            if ($result) {
                return $result->fetch_array(MYSQLI_NUM);
            }

            //return false if no results
            return false;
        }

        mysqli_close($link);
    }

    public function store($token)
    {
        $link = $this->connect();

        $query = "INSERT INTO `admin_tokens` (id, is_access_token) VALUES(1, ?) ON DUPLICATE KEY UPDATE is_access_token = VALUES(is_access_token)";

        $stmt = $link->prepare($query);

        if ($stmt) {
            $stmt->bind_param('s', $token);

            $ex = $stmt->execute();

            if ($ex === false) {
                die('Execute() failed: '.htmlspecialchars($stmt->error));
            }

            $stmt->close();
        }

        mysqli_close($link);

        return true;
    }
}
