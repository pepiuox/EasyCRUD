<?php

define('DBHOST', ''); // Add your host
define('DBUSER', ''); // Add your username
define('DBPASS', ''); // Add your password
define('DBNAME', ''); // Add your database name
//MySQLi Object / Procedural
$link = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if (!$link) {
    die('Error: Could not connect: ' . mysqli_error());
}
//PDO Object / Procedural
class DB {

    protected $conn = null;

    public function Connect() {
        try {
            $hostDB = DBHOST;
            $baseDB = DBNAME;
            $userDB = DBUSER;
            $passDB = DBPASS;
            $charset = 'utf8';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $dsn = "mysql:host=$hostDB;dbname=$baseDB;charset=$charset";
            $this->conn = new PDO($dsn, $userDB, $passDB, $options);
            return $this->conn;
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
    }

    public function Close() {
        $this->conn = null;
    }
}

?>
