<?php
// Importamos todos los modelos necesarios
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Producto.php';
require_once __DIR__ . '/../Models/Venta.php';
require_once __DIR__ . '/../Models/Reporte.php';

class DashboardController {
    
// --- SECCIÓN DE ESTADÍSTICAS (HOME) ---
    public function stats() {
        // Pedimos los datos REALES al modelo Reporte
        $ventas_hoy = Reporte::getVentasHoy();
        $transacciones_hoy = Reporte::getTransaccionesHoy();
        $stock_total = Reporte::getStockTotal();
        $usuarios_activos = Reporte::getUsuariosActivos();
        
        // Datos para la vista
        $data = [
            'section' => 'stats',
            'ventas_dia' => $ventas_hoy,
            'transacciones_hoy' => $transacciones_hoy,
            'stock_total' => $stock_total,
            'usuarios_activos' => $usuarios_activos,
            'porcentaje_ayer' => 0, // (Pendiente: Calcular vs ayer requeriría más lógica)
            'ordenes_pendientes' => 0 // (Pendiente: Si implementamos estados de pedido)
        ];

        $this->render('stats', $data);
    }

    // ------------------------------------------------
    // SECCIÓN 2: USUARIOS
    // ------------------------------------------------
    public function usuarios() {
        $filtroRol = isset($_GET['rol']) ? $_GET['rol'] : null;
        $usuarios = Usuario::all($filtroRol);
        
        $data = [
            'section' => 'usuarios', 
            'lista_usuarios' => $usuarios,
            'filtro_actual' => $filtroRol
        ];
        $this->render('usuarios', $data);
    }

    public function crearUsuario() {
        $data = ['section' => 'usuarios'];
        $this->render('usuarios_crear', $data);
    }

    public function guardarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'sucursal' => $_POST['sucursal']
            ];
            Usuario::create($datos);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function editarUsuario() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $usuario = Usuario::find($id);
            
            $data = ['section' => 'usuarios', 'usuario' => $usuario];
            $this->render('usuarios_editar', $data);
        } else {
            header("Location: ?section=usuarios");
        }
    }

    public function actualizarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id' => $_POST['id'],
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'sucursal' => $_POST['sucursal']
            ];
            Usuario::update($datos);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function eliminarUsuario() {
        if (isset($_GET['id'])) {
            Usuario::delete($_GET['id']);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function cambiarEstadoUsuario() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            Usuario::cambiarEstado($_GET['id'], $_GET['estado']);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    // --- INVENTARIO / PRODUCTOS ---
    public function inventario() {
        // 1. Verificamos si hay filtro en la URL
        $filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        // 2. Pedimos los productos filtrados al modelo
        $productos = Producto::all($filtroCategoria);
        
        $data = [
            'section' => 'inventario', 
            'lista_productos' => $productos,
            'filtro_actual' => $filtroCategoria // Para mantener seleccionado el filtro
        ];
        
        $this->render('inventario', $data);
    }

    public function crearProducto() {
        $data = ['section' => 'inventario'];
        $this->render('productos_crear', $data);
    }

    public function guardarProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'],
                'categoria' => $_POST['categoria'],
                'stock_actual' => $_POST['stock_actual'],
                'stock_minimo' => $_POST['stock_minimo'],
                'precio' => $_POST['precio']
            ];
            Producto::create($datos);
            header("Location: ?section=inventario");
            exit();
        }
    }

    public function editarProducto() {
        // SI AQUÍ FALLA EL "find", DA PANTALLA BLANCA
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $producto = Producto::find($id);
            
            $data = ['section' => 'inventario', 'producto' => $producto];
            $this->render('productos_editar', $data);
        }
    }

    public function actualizarProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id' => $_POST['id'],
                'nombre' => $_POST['nombre'],
                'categoria' => $_POST['categoria'],
                'stock_actual' => $_POST['stock_actual'],
                'stock_minimo' => $_POST['stock_minimo'],
                'precio' => $_POST['precio']
            ];
            Producto::update($datos);
            header("Location: ?section=inventario");
            exit();
        }
    }

    public function eliminarProducto() {
        if (isset($_GET['id'])) {
            Producto::delete($_GET['id']);
            header("Location: ?section=inventario");
            exit();
        }
    }

    // ------------------------------------------------
    // SECCIÓN 4: VENTAS
    // ------------------------------------------------
    public function ventas() {
        // Filtros
        $fechaInicio = isset($_GET['inicio']) ? $_GET['inicio'] : null;
        $fechaFin = isset($_GET['fin']) ? $_GET['fin'] : null;

        $lista_ventas = Venta::all($fechaInicio, $fechaFin);
        
        $data = [
            'section' => 'ventas',
            'lista_ventas' => $lista_ventas,
            'inicio' => $fechaInicio,
            'fin' => $fechaFin,
            
            // KPIs REALES CONECTADOS AL MODELO
            'ventas_hoy_total' => Reporte::getVentasHoy(),
            'ventas_hoy_cantidad' => Reporte::getTransaccionesHoy(),
            'ventas_semana' => Reporte::getVentasSemana(),     // <--- NUEVO
            'ticket_promedio' => Reporte::getTicketPromedio()  // <--- NUEVO
        ];
        $this->render('ventas', $data);
    }

    // ------------------------------------------------
    // SECCIÓN 5: REPORTES
    // ------------------------------------------------
    public function reportes() {
        // 1. Capturar filtros de fecha de la URL
        $inicio = isset($_GET['inicio']) ? $_GET['inicio'] : null;
        $fin = isset($_GET['fin']) ? $_GET['fin'] : null;

        // 2. Pedir datos filtrados al Modelo
        // Pasamos ($inicio, $fin) a todas las funciones
        $ventasSucursal = Reporte::getVentasPorSucursal($inicio, $fin);
        $topProductos = Reporte::getTopProductos($inicio, $fin);
        $rendimiento = Reporte::getRendimientoVendedores($inicio, $fin);
        $metricas = Reporte::getMetricasClave($inicio, $fin);
        $detalleVentas = Reporte::getDetalleVentas($inicio, $fin);

        $totalVentas = array_sum($ventasSucursal);

        $data = [
            'section' => 'reportes',
            'ventas_sucursal' => $ventasSucursal,
            'total_ventas' => $totalVentas,
            'top_productos' => $topProductos,
            'rendimiento_vendedores' => $rendimiento,
            'metricas' => $metricas,
            'tabla_detalle' => $detalleVentas,
            // Pasamos las fechas a la vista para mantenerlas en los inputs
            'inicio' => $inicio,
            'fin' => $fin
        ];

        $this->render('reportes', $data);
    }

    // ------------------------------------------------
    // HELPER: RENDERIZAR VISTAS
    // ------------------------------------------------
    private function render($viewName, $data = []) {
        extract($data);
        $viewsPath = __DIR__ . '/../Views/';
        
        // Verificamos que el archivo exista para evitar errores silenciosos
        if (file_exists($viewsPath . "dashboard/$viewName.php")) {
            require_once $viewsPath . 'layouts/header.php';
            require_once $viewsPath . 'layouts/navigation.php';
            require_once $viewsPath . "dashboard/$viewName.php";
            require_once $viewsPath . 'layouts/footer.php';
        } else {
            echo "<h1>Error: No se encuentra la vista 'app/Views/dashboard/$viewName.php'</h1>";
        }
    }

    // --- VENTAS ---
    
    // ... tu método ventas() sigue igual ...

    // 1. Formulario de Nueva Venta
    public function crearVenta() {
        // Necesitamos la lista de productos para el <select>
        // Solo cargamos productos con stock > 0 para no vender aire
        $productos = Producto::all(); // (Nota: Podríamos filtrar por stock > 0 aquí)
        
        $data = [
            'section' => 'ventas',
            'lista_productos' => $productos
        ];
        $this->render('ventas_crear', $data);
    }

    // 2. Procesar la Venta
    public function guardarVenta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Datos generales
            $cliente = $_POST['cliente'];
            $vendedor = $_SESSION['usuario_nombre']; // ¡El usuario logueado!
            
            // Datos del producto seleccionado
            $id_producto = $_POST['producto_id'];
            $cantidad = $_POST['cantidad'];
            
            // Buscamos el producto para saber su precio y validar stock
            $producto = Producto::find($id_producto);

            // VALIDACIÓN: ¿Hay suficiente stock?
            if ($producto['stock_actual'] < $cantidad) {
                echo "<script>alert('Error: No hay suficiente stock para realizar esta venta.'); window.history.back();</script>";
                exit();
            }

            $precio_unitario = $producto['precio'];
            $total_venta = $precio_unitario * $cantidad;

            // Preparamos los arrays para el Modelo
            $datos_venta = [
                'cliente' => $cliente,
                'vendedor' => $vendedor,
                'total' => $total_venta
            ];

            $item = [
                'id_producto' => $id_producto,
                'cantidad' => $cantidad,
                'precio_unitario' => $precio_unitario
            ];

            try {
                Venta::create($datos_venta, $item);
                header("Location: ?section=ventas");
                exit();
            } catch (Exception $e) {
                echo "Error al procesar la venta: " . $e->getMessage();
            }
        }
    }

    // NUEVO: Ver Detalle / Factura
    public function verVenta() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $venta = Venta::find($id);
            $detalles = Venta::getDetalles($id);
            
            $data = [
                'section' => 'ventas',
                'venta' => $venta,
                'detalles' => $detalles
            ];
            $this->render('ventas_detalle', $data);
        }
    }

    // NUEVO: Procesar Venta (De pendiente a completada)
    public function procesarVenta() {
        if (isset($_GET['id'])) {
            Venta::procesar($_GET['id']);
            header("Location: ?section=ventas");
            exit();
        }
    }

    // NUEVO: Cancelar Venta
    public function cancelarVenta() {
        if (isset($_GET['id'])) {
            Venta::cancelar($_GET['id']);
            header("Location: ?section=ventas");
            exit();
        }
    }

} // <--- FIN DE LA CLASE (Esta llave es crítica)