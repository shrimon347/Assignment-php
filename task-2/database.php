<?php
interface ICrud {
    public function insert($brand, $model, $year, $price); 
    public function getAll(); 
    public function update($id, $brand = null, $model = null, $year = null, $price = null); 
    public function delete($id); 
    public function search($keyword); 
    public function filter($filters);
}

abstract class Database implements ICrud {
    protected $conn;

    public function __construct() {
        $host = 'db'; 
        $dbname = 'main_db'; 
        $user = 'user';
        $pass = 'password'; 

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
