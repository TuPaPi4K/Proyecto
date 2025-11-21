<?php
require_once __DIR__ . '/../../config/database.php';

class Venta {
    public static function all() {
        $database = new Database();
        $db = $database->getConnection();
        
        // Alias: codigo_venta AS id_venta para que la vista no se rompa
        $query = "SELECT 
                    id,
                    codigo_venta as id_venta,
                    fecha,
                    cliente,
                    vendedor,
                    total,
                    estado
                  FROM ventas
                  ORDER BY fecha DESC"; // Ordenamos por fecha (más reciente primero)
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}