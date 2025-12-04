<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'pollo_naguara';
    private $username = 'root';     // Usuario por defecto de XAMPP
    private $password = '';         // Contraseña por defecto de XAMPP (vacía)
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Intentamos conectar
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Configuramos para que nos avise si hay errores 
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configuramos para que entienda tildes y ñ (UTF-8)
            $this->conn->exec("set names utf8");
            
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}