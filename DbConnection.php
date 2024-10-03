<?php
class DbConnection
{
    private $conn;
    // Database configuration
    private $host = 'localhost';
    private $dbname = 'test';
    private $username = 'root';
    private $password = '';

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            die("Connection failed: " . $error->getMessage());
        }
    }

    public function getConnection() // Added method to retrieve the connection
    {
        return $this->conn;
    }

    public function __destruct()
    {
        $this->conn = null; // Ensure connection is closed when the object is destroyed
    }
}
