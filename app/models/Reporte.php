<?php
require_once __DIR__ . '/../../config/database.php';

class Reporte {
    
    // 1. Ventas por Sucursal (Requiere unir Ventas con Usuarios)
    public static function getVentasPorSucursal() {
        $db = (new Database())->getConnection();
        
        // OJO: Estamos uniendo por nombre de vendedor porque tu tabla ventas
        // guardó el nombre en texto, no el ID. En el futuro deberíamos corregir eso.
        $query = "SELECT 
                    u.sucursal, 
                    SUM(v.total) as total 
                  FROM ventas v
                  JOIN usuarios u ON v.vendedor = u.nombre
                  GROUP BY u.sucursal";
                  
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        // Formateamos para que devuelva array clave=>valor ('Centro' => 1500)
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach($resultados as $fila) {
            $data[$fila['sucursal']] = $fila['total'];
        }
        return $data;
    }

    // 2. Top Productos (Requiere unir Detalle con Productos)
    public static function getTopProductos() {
        $db = (new Database())->getConnection();
        
        $query = "SELECT 
                    p.nombre, 
                    SUM(d.cantidad) as total_vendidos 
                  FROM detalle_ventas d
                  JOIN productos p ON d.id_producto = p.id
                  GROUP BY p.nombre
                  ORDER BY total_vendidos DESC
                  LIMIT 5";
                  
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach($resultados as $fila) {
            $data[$fila['nombre']] = $fila['total_vendidos'];
        }
        return $data;
    }

    // 3. Rendimiento Vendedores
    public static function getRendimientoVendedores() {
        $db = (new Database())->getConnection();
        
        $query = "SELECT vendedor, SUM(total) as total_vendido 
                  FROM ventas 
                  GROUP BY vendedor 
                  ORDER BY total_vendido DESC";
                  
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach($resultados as $fila) {
            $data[$fila['vendedor']] = $fila['total_vendido'];
        }
        return $data;
    }

    // 4. Métricas Clave (Cálculos varios)
    public static function getMetricasClave() {
        $db = (new Database())->getConnection();
        
        // Ticket Promedio
        $stmt = $db->prepare("SELECT AVG(total) as ticket FROM ventas");
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC)['ticket'];

        return [
            'ticket_promedio' => number_format($ticket, 2),
            'conversion' => '68%',    // Dato hardcodeado (no tenemos visitas web para calcularlo)
            'recurrentes' => '42%',   // Dato hardcodeado (requiere lógica de clientes compleja)
            'crecimiento' => '+15%'   // Dato hardcodeado
        ];
    }

    // 5. Tabla Detallada (JOIN de 4 tablas: Ventas, Detalles, Productos, Usuarios)
    public static function getDetalleVentas() {
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
                  LEFT JOIN usuarios u ON v.vendedor = u.nombre -- LEFT JOIN por si el vendedor fue borrado
                  ORDER BY v.fecha DESC
                  LIMIT 20";
                  
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}