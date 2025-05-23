<?php
class DataAccess {
    private $pdo;

    public function __construct($host, $db, $user, $pass) {
        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit; 
        }
    }

    public function GetData($query) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>