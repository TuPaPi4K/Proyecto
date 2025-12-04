<?php
require_once __DIR__ . '/../../config/database.php';

class Venta {
    // Listar ventas con FILTRO DE FECHAS
    public static function all($fechaInicio = null, $fechaFin = null) {
        $db = (new Database())->getConnection();
        
        $query = "SELECT 
                    id,
                    codigo_venta as id_venta,
                    fecha,
                    cliente,
                    vendedor,
                    total,
                    estado
                  FROM ventas";
        
        $params = [];
        
        // Lógica de filtrado
        if ($fechaInicio && $fechaFin) {
            $query .= " WHERE DATE(fecha) BETWEEN :inicio AND :fin";
            $params[':inicio'] = $fechaInicio;
            $params[':fin'] = $fechaFin;
        }
        
        $query .= " ORDER BY fecha DESC";

        $stmt = $db->prepare($query);
        
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear Venta 
    public static function create($datos_venta, $item) {
        $db = (new Database())->getConnection();
        try {
            $db->beginTransaction();

            // Cabecera
            $queryVenta = "INSERT INTO ventas (codigo_venta, cliente, vendedor, total, estado) 
                           VALUES (:codigo, :cliente, :vendedor, :total, 'Completada')";
            $stmtV = $db->prepare($queryVenta);
            $codigo = '#VN-' . time();
            $stmtV->execute([
                ':codigo' => $codigo, 
                ':cliente' => $datos_venta['cliente'], 
                ':vendedor' => $datos_venta['vendedor'], 
                ':total' => $datos_venta['total']
            ]);
            $id_venta = $db->lastInsertId();

            // Detalle
            $queryDetalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario) 
                             VALUES (:id_venta, :id_producto, :cantidad, :precio)";
            $stmtD = $db->prepare($queryDetalle);
            $stmtD->execute([
                ':id_venta' => $id_venta,
                ':id_producto' => $item['id_producto'],
                ':cantidad' => $item['cantidad'],
                ':precio' => $item['precio_unitario']
            ]);

            // Descontar Stock
            $queryStock = "UPDATE productos SET stock_actual = stock_actual - :cantidad WHERE id = :id";
            $stmtS = $db->prepare($queryStock);
            $stmtS->execute([':cantidad' => $item['cantidad'], ':id' => $item['id_producto']]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    // Obtener UNA venta por ID (Para ver detalle)
    public static function find($id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM ventas WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener los productos de una venta
    public static function getDetalles($id_venta) {
        $db = (new Database())->getConnection();
        $query = "SELECT d.*, p.nombre as producto_nombre 
                  FROM detalle_ventas d
                  JOIN productos p ON d.id_producto = p.id
                  WHERE d.id_venta = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id_venta);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Procesar (Simplemente cambiar estado)
    public static function procesar($id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("UPDATE ventas SET estado = 'Completada' WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // CANCELAR (Devolver stock y cambiar estado)
    public static function cancelar($id) {
        $db = (new Database())->getConnection();
        try {
            $db->beginTransaction();

            // Cambiar estado a Cancelada
            $stmt = $db->prepare("UPDATE ventas SET estado = 'Cancelada' WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Recuperar los productos para devolverlos al inventario
            $detalles = self::getDetalles($id);
            
            $queryStock = "UPDATE productos SET stock_actual = stock_actual + :cantidad WHERE id = :id";
            $stmtStock = $db->prepare($queryStock);

            foreach ($detalles as $detalle) {
                $stmtStock->execute([
                    ':cantidad' => $detalle['cantidad'],
                    ':id' => $detalle['id_producto']
                ]);
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}