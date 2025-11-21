<?php
require_once __DIR__ . '/../../config/database.php';

class Producto {
    public static function all() {
        $database = new Database();
        $db = $database->getConnection();
        
        // Consulta SQL
        $query = "SELECT 
                    id, 
                    nombre, 
                    categoria, 
                    stock_actual, 
                    stock_minimo, 
                    precio,
                    IF(stock_actual <= stock_minimo, 'Stock Bajo', 'En Stock') as estado
                  FROM productos";
        
        $stmt = $db->prepare($query);
        
        // CORRECCIÓN AQUÍ: Usar -> en lugar de .
        $stmt->execute(); 
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear nuevo producto
    public static function create($data) {
        $db = (new Database())->getConnection();

        $query = "INSERT INTO productos (nombre, categoria, stock_actual, stock_minimo, precio) 
                  VALUES (:nombre, :categoria, :stock, :minimo, :precio)";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':categoria', $data['categoria']);
        $stmt->bindParam(':stock', $data['stock_actual']);
        $stmt->bindParam(':minimo', $data['stock_minimo']);
        $stmt->bindParam(':precio', $data['precio']);
        
        return $stmt->execute();
    }

    // Buscar por ID (Para editar)
    public static function find($id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar producto
    public static function update($data) {
        $db = (new Database())->getConnection();
        
        $query = "UPDATE productos SET 
                    nombre = :nombre, 
                    categoria = :categoria, 
                    stock_actual = :stock, 
                    stock_minimo = :minimo, 
                    precio = :precio 
                  WHERE id = :id";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':categoria', $data['categoria']);
        $stmt->bindParam(':stock', $data['stock_actual']);
        $stmt->bindParam(':minimo', $data['stock_minimo']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':id', $data['id']);
        
        return $stmt->execute();
    }

    // Eliminar producto
    public static function delete($id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("DELETE FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

}