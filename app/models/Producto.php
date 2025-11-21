<?php
require_once __DIR__ . '/../../config/database.php';

class Producto {
    
    // 1. Listar productos (Combina filtro de categoría + Solo activos)
    public static function all($categoria = null) {
        $db = (new Database())->getConnection();
        
        // Consulta base: Solo productos activos (Soft Delete check)
        $query = "SELECT 
                    id, 
                    nombre, 
                    categoria, 
                    stock_actual, 
                    stock_minimo, 
                    precio,
                    IF(stock_actual <= stock_minimo, 'Stock Bajo', 'En Stock') as estado
                  FROM productos 
                  WHERE activo = 1"; 

        // Si hay categoría seleccionada, agregamos el filtro AND
        if ($categoria && $categoria != 'Todas las categorías') {
            $query .= " AND categoria = :categoria";
        }

        $stmt = $db->prepare($query);

        // Vinculamos el parámetro si existe
        if ($categoria && $categoria != 'Todas las categorías') {
            $stmt->bindParam(':categoria', $categoria);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Crear (Insertamos con activo = 1 por defecto)
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

    // 3. Buscar por ID (Solo si está activo)
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

    // 5. Eliminar (Soft Delete: Update activo = 0)
    public static function delete($id) {
        $db = (new Database())->getConnection();
        
        $query = "UPDATE productos SET activo = 0 WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}