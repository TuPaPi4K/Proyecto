<?php
require_once __DIR__ . '/../../config/database.php';

class Reporte {
    
    // ==========================================
    // SECCIÓN 1: KPIs (DATOS RÁPIDOS PARA INICIO)
    // (Estos se quedan igual porque son para "Hoy" o "Total")
    // ==========================================

    public static function getVentasHoy() {
        $db = (new Database())->getConnection();
        $query = "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha) = CURDATE()";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ? $resultado['total'] : 0;
    }

    public static function getTransaccionesHoy() {
        $db = (new Database())->getConnection();
        $query = "SELECT COUNT(*) as cantidad FROM ventas WHERE DATE(fecha) = CURDATE()";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['cantidad'];
    }

    public static function getStockTotal() {
        $db = (new Database())->getConnection();
        $query = "SELECT SUM(stock_actual) as total FROM productos WHERE activo = 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public static function getUsuariosActivos() {
        $db = (new Database())->getConnection();
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // KPIs de Ventas (Semana y Ticket)
    public static function getVentasSemana() {
        $db = (new Database())->getConnection();
        $query = "SELECT SUM(total) as total FROM ventas WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ? $resultado['total'] : 0;
    }

    public static function getTicketPromedio() {
        $db = (new Database())->getConnection();
        $query = "SELECT AVG(total) as promedio FROM ventas";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['promedio'] ? $resultado['promedio'] : 0;
    }

    // ==========================================
    // SECCIÓN 2: REPORTES FILTRABLES (CON FECHAS)
    // ==========================================

    // Helper privado para construir el WHERE de fechas
    private static function aplicarFiltros($sql, $inicio, $fin) {
        if ($inicio && $fin) {
            // Si ya tiene WHERE, agregamos AND, si no, agregamos WHERE
            $operador = strpos($sql, 'WHERE') !== false ? ' AND ' : ' WHERE ';
            $sql .= $operador . "DATE(v.fecha) BETWEEN :inicio AND :fin";
        }
        return $sql;
    }

    // 7. Ventas por Sucursal (Filtrable)
    public static function getVentasPorSucursal($inicio = null, $fin = null) {
        $db = (new Database())->getConnection();
        
        $query = "SELECT u.sucursal, SUM(v.total) as total 
                  FROM ventas v
                  JOIN usuarios u ON v.vendedor = u.nombre";
        
        // Aplicamos filtro
        $query = self::aplicarFiltros($query, $inicio, $fin);
        $query .= " GROUP BY u.sucursal";

        $stmt = $db->prepare($query);
        if ($inicio && $fin) {
            $stmt->bindParam(':inicio', $inicio);
            $stmt->bindParam(':fin', $fin);
        }
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach($resultados as $fila) {
            $data[$fila['sucursal']] = $fila['total'];
        }
        return $data;
    }

    // 8. Top Productos (Filtrable)
    public static function getTopProductos($inicio = null, $fin = null) {
        $db = (new Database())->getConnection();
        
        $query = "SELECT p.nombre, SUM(d.cantidad) as total_vendidos 
                  FROM detalle_ventas d
                  JOIN ventas v ON d.id_venta = v.id
                  JOIN productos p ON d.id_producto = p.id";
        
        $query = self::aplicarFiltros($query, $inicio, $fin);
        $query .= " GROUP BY p.nombre ORDER BY total_vendidos DESC LIMIT 5";

        $stmt = $db->prepare($query);
        if ($inicio && $fin) {
            $stmt->bindParam(':inicio', $inicio);
            $stmt->bindParam(':fin', $fin);
        }
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach($resultados as $fila) {
            $data[$fila['nombre']] = $fila['total_vendidos'];
        }
        return $data;
    }

    // 9. Rendimiento Vendedores (Filtrable)
    public static function getRendimientoVendedores($inicio = null, $fin = null) {
        $db = (new Database())->getConnection();
        
        $query = "SELECT vendedor, SUM(total) as total_vendido FROM ventas v";
        
        $query = self::aplicarFiltros($query, $inicio, $fin);
        $query .= " GROUP BY vendedor ORDER BY total_vendido DESC";

        $stmt = $db->prepare($query);
        if ($inicio && $fin) {
            $stmt->bindParam(':inicio', $inicio);
            $stmt->bindParam(':fin', $fin);
        }
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach($resultados as $fila) {
            $data[$fila['vendedor']] = $fila['total_vendido'];
        }
        return $data;
    }

    // 10. Métricas Clave (Filtrable)
    public static function getMetricasClave($inicio = null, $fin = null) {
        $db = (new Database())->getConnection();
        
        // Ticket Promedio del periodo
        $query = "SELECT AVG(total) as ticket FROM ventas v";
        $query = self::aplicarFiltros($query, $inicio, $fin);
        
        $stmt = $db->prepare($query);
        if ($inicio && $fin) {
            $stmt->bindParam(':inicio', $inicio);
            $stmt->bindParam(':fin', $fin);
        }
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC)['ticket'];

        return [
            'ticket_promedio' => number_format($ticket ? $ticket : 0, 2),
            'conversion' => '68%', // Fijo por ahora
            'recurrentes' => '42%', // Fijo por ahora
            'crecimiento' => '+15%' // Fijo por ahora
        ];
    }

    // 11. Tabla Detallada (Filtrable)
    public static function getDetalleVentas($inicio = null, $fin = null) {
        $db = (new Database())->getConnection();
        
        $query = "SELECT 
                    v.fecha, 
                    p.nombre as producto, 
                    d.cantidad, 
                    (d.cantidad * d.precio_unitario) as total,
                    u.sucursal,
                    v.vendedor
                  FROM detalle_ventas d
                  JOIN ventas v ON d.id_venta = v.id
                  JOIN productos p ON d.id_producto = p.id
                  LEFT JOIN usuarios u ON v.vendedor = u.nombre";
        
        $query = self::aplicarFiltros($query, $inicio, $fin);
        $query .= " ORDER BY v.fecha DESC"; // Quitamos el LIMIT para que el reporte sea completo

        $stmt = $db->prepare($query);
        if ($inicio && $fin) {
            $stmt->bindParam(':inicio', $inicio);
            $stmt->bindParam(':fin', $fin);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}