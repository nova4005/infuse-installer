<?php

class MySQL_DB {


    function __construct($databaseName, $username, $password) {
        $this->db = $databaseName;
        $this->username = $username;
        $this->password = $password;
    }


    public function generate_connection() {
        return "mysqli_connect('localhost', '$this->username', '$this->password', '$this->db')";
    }
}
