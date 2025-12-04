<?php
require_once __DIR__ . '/../../config/database.php';

class Producto {
    
    // 1. Listar productos con estados inteligentes
    public static function all($categoria = null) {
        $db = (new Database())->getConnection();
        
        // AQUÍ ESTÁ LA CLAVE: El CASE evalúa en orden.
        // Primero preguntamos si es <= 0. Si es sí, le pone 'Sin Stock' y deja de evaluar.
        $query = "SELECT 
                    id, 
                    nombre, 
                    categoria, 
                    stock_actual, 
                    stock_minimo, 
                    precio,
                    CASE 
                        WHEN stock_actual <= 0 THEN 'Sin Stock'
                        WHEN stock_actual <= stock_minimo THEN 'Stock Bajo'
                        ELSE 'En Stock'
                    END as estado
                  FROM productos 
                  WHERE activo = 1"; 

        if ($categoria && $categoria != 'Todas las categorías') {
            $query .= " AND categoria = :categoria";
        }

        $stmt = $db->prepare($query);

        if ($categoria && $categoria != 'Todas las categorías') {
            $stmt->bindParam(':categoria', $categoria);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Crear
    public static function create($data) {
        $db = (new Database())->getConnection();
        $query = "INSERT INTO productos (nombre, categoria, stock_actual, stock_minimo, precio, activo) 
                  VALUES (:nombre, :categoria, :stock, :minimo, :precio, 1)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':categoria', $data['categoria']);
        $stmt->bindParam(':stock', $data['stock_actual']);
        $stmt->bindParam(':minimo', $data['stock_minimo']);
        $stmt->bindParam(':precio', $data['precio']);
        return $stmt->execute();
    }

    // 3. Buscar por ID
    public static function find($id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Actualizar
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

    // 5. Borrado Lógico (Soft Delete)
    public static function delete($id) {
        $db = (new Database())->getConnection();
        $query = "UPDATE productos SET activo = 0 WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}