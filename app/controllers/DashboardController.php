<?php
// Importamos todos los modelos necesarios
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Producto.php';
require_once __DIR__ . '/../Models/Venta.php';
require_once __DIR__ . '/../Models/Reporte.php';

class DashboardController {
    
    // --- SECCIÓN DE ESTADÍSTICAS (ACCESO PARA TODOS) ---
    public function stats() {
        $ventas_hoy = Reporte::getVentasHoy();
        $transacciones_hoy = Reporte::getTransaccionesHoy();
        $stock_total = Reporte::getStockTotal();
        $usuarios_activos = Reporte::getUsuariosActivos();
        
        $data = [
            'section' => 'stats',
            'ventas_dia' => $ventas_hoy,
            'transacciones_hoy' => $transacciones_hoy,
            'stock_total' => $stock_total,
            'usuarios_activos' => $usuarios_activos,
            'porcentaje_ayer' => 0,
            'ordenes_pendientes' => 0
        ];

        $this->render('stats', $data);
    }

    // ------------------------------------------------
    // SECCIÓN 2: USUARIOS (SOLO ADMINISTRADORES)
    // ------------------------------------------------
    public function usuarios() {
        // SEGURIDAD: Solo Administradores
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

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
        // SEGURIDAD
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        $data = ['section' => 'usuarios'];
        $this->render('usuarios_crear', $data);
    }

    public function guardarUsuario() {
        // SEGURIDAD
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Encriptamos la contraseña
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $datos = [
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'password' => $password_hash, // <--- Agregamos esto
                'rol' => $_POST['rol'],
                'sucursal' => $_POST['sucursal']
            ];
            
            Usuario::create($datos);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function editarUsuario() {
        // SEGURIDAD
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

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
        // SEGURIDAD
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

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
        // SEGURIDAD
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        if (isset($_GET['id'])) {
            Usuario::delete($_GET['id']);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function cambiarEstadoUsuario() {
        // SEGURIDAD
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        if (isset($_GET['id']) && isset($_GET['estado'])) {
            Usuario::cambiarEstado($_GET['id'], $_GET['estado']);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    // --- INVENTARIO / PRODUCTOS (ACCESO PARA TODOS) ---
    public function inventario() {
        $filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
        $productos = Producto::all($filtroCategoria);
        
        $data = [
            'section' => 'inventario', 
            'lista_productos' => $productos,
            'filtro_actual' => $filtroCategoria
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

    // --- VENTAS (ACCESO PARA TODOS) ---
    public function ventas() {
        $fechaInicio = isset($_GET['inicio']) ? $_GET['inicio'] : null;
        $fechaFin = isset($_GET['fin']) ? $_GET['fin'] : null;

        $lista_ventas = Venta::all($fechaInicio, $fechaFin);
        
        $data = [
            'section' => 'ventas',
            'lista_ventas' => $lista_ventas,
            'inicio' => $fechaInicio,
            'fin' => $fechaFin,
            'ventas_hoy_total' => Reporte::getVentasHoy(),
            'ventas_hoy_cantidad' => Reporte::getTransaccionesHoy(),
            'ventas_semana' => Reporte::getVentasSemana(),
            'ticket_promedio' => Reporte::getTicketPromedio()
        ];
        $this->render('ventas', $data);
    }

    public function crearVenta() {
        $productos = Producto::all();
        $data = [
            'section' => 'ventas',
            'lista_productos' => $productos
        ];
        $this->render('ventas_crear', $data);
    }

    public function guardarVenta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = $_POST['cliente'];
            $vendedor = $_SESSION['usuario_nombre'];
            $id_producto = $_POST['producto_id'];
            $cantidad = $_POST['cantidad'];
            
            $producto = Producto::find($id_producto);

            if ($producto['stock_actual'] < $cantidad) {
                echo "<script>alert('Error: No hay suficiente stock.'); window.history.back();</script>";
                exit();
            }

            $precio_unitario = $producto['precio'];
            $total_venta = $precio_unitario * $cantidad;

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
                echo "Error: " . $e->getMessage();
            }
        }
    }

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

    public function procesarVenta() {
        if (isset($_GET['id'])) {
            Venta::procesar($_GET['id']);
            header("Location: ?section=ventas");
            exit();
        }
    }

    public function cancelarVenta() {
        if (isset($_GET['id'])) {
            Venta::cancelar($_GET['id']);
            header("Location: ?section=ventas");
            exit();
        }
    }

    // --- REPORTES (ACCESO PARA TODOS) ---
    public function reportes() {
        $inicio = isset($_GET['inicio']) ? $_GET['inicio'] : null;
        $fin = isset($_GET['fin']) ? $_GET['fin'] : null;

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
            'inicio' => $inicio,
            'fin' => $fin
        ];

        $this->render('reportes', $data);
    }

    // --- HELPER: RENDERIZAR VISTAS ---
    private function render($viewName, $data = []) {
        extract($data);
        $viewsPath = __DIR__ . '/../Views/';
        
        if (file_exists($viewsPath . "dashboard/$viewName.php")) {
            require_once $viewsPath . 'layouts/header.php';
            require_once $viewsPath . 'layouts/navigation.php';
            require_once $viewsPath . "dashboard/$viewName.php";
            require_once $viewsPath . 'layouts/footer.php';
        } else {
            echo "<h1>Error: No se encuentra la vista 'app/Views/dashboard/$viewName.php'</h1>";
        }
    }

    // 7. Restablecer contraseña a '123456'
    public function resetearClaveUsuario() {
        // SEGURIDAD
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // Contraseña temporal: 123456
            $password_hash = password_hash("123456", PASSWORD_DEFAULT);
            
            Usuario::resetPassword($id, $password_hash);
            
            // Opcional: Podrías mandar un mensaje de éxito con JS
            echo "<script>alert('Contraseña restablecida a: 123456'); window.location.href='?section=usuarios';</script>";
            exit();
        }
    }

    // --- MI PERFIL (Cambio de Clave Personal) ---
    
    public function perfil() {
        // Esta pantalla la puede ver CUALQUIERA logueado (Admin o Vendedor)
        $data = ['section' => 'perfil']; // section 'perfil' no existe en menu, no marcará nada, está bien
        $this->render('perfil', $data);
    }

    public function guardarPerfil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pass1 = $_POST['password'];
            $pass2 = $_POST['confirm_password'];

            if ($pass1 !== $pass2) {
                echo "<script>alert('Error: Las contraseñas no coinciden.'); window.history.back();</script>";
                exit();
            }

            if (strlen($pass1) < 4) {
                echo "<script>alert('Error: La contraseña es muy corta.'); window.history.back();</script>";
                exit();
            }

            // Obtenemos el ID del usuario conectado
            $id_usuario = $_SESSION['usuario_id'];
            $hash = password_hash($pass1, PASSWORD_DEFAULT);

            // Reutilizamos el método resetPassword que ya creamos en el Modelo
            // (Funciona igual: actualiza la pass del ID indicado)
            Usuario::resetPassword($id_usuario, $hash);

            echo "<script>alert('¡Contraseña actualizada con éxito!'); window.location.href='?section=stats';</script>";
            exit();
        }
    }

} // FIN DE LA CLASE