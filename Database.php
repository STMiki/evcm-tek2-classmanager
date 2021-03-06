<?php

require_once('log.php');

class Database {

    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "etvoilacfyevcmbd";
    private $username = "username";
    private $password = "password";
    public $conn;

    // get the database connection
    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            printLog(__METHOD__, 'Connection error : '.$exception->getMessage().'\nVersion de PHP : '.phpversion());
        }
        return ($this->conn);
    }
}
