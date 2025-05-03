<?php 
class database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $database = "privnote";

    public function __construct($host, $user, $pass, $database) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
    }
    public function pdo() {
        try{
            $pdo = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;    
        } catch(PDOException $e){
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }
    public function query($sql) {
        $pdo = $this->pdo();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
    public function insert($table, $data) {
        $pdo = $this->pdo();
        $keys = implode(',', array_keys($data));
        $values = ":" . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return $pdo->lastInsertId();
    }
    public function update($table, $data, $where) {
        $pdo = $this->pdo();
        $set = '';
        foreach($data as $key => $value) {
            $set .= $key . " = :".$key.", ";
        }
        $set = rtrim($set, ', ');
        $sql = "UPDATE $table SET $set WHERE $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
    public function delete($table, $where) {
        $pdo = $this->pdo();
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function dissconnect() {
        $pdo = null;
    }
}
?>