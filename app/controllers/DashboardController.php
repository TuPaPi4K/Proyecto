<?php
// Importamos todos los modelos necesarios
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Producto.php';
require_once __DIR__ . '/../Models/Venta.php';
require_once __DIR__ . '/../Models/Reporte.php';
// Agregamos el modelo de configuración
require_once __DIR__ . '/../Models/Configuracion.php';

class DashboardController {
    
    // --- SECCIÓN DE ESTADÍSTICAS ---
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
            'usuarios_activos' => $usuarios_activos
        ];

        $this->render('stats', $data);
    }

    // --- NUEVA SECCIÓN: CONFIGURACIÓN ---
    public function configuracion() {
        // SEGURIDAD: Solo Administradores
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        // Obtener datos
        $config = Configuracion::get(); 
        
        // Renderizar vista
        // AQUI ESTABA EL ERROR: Agregamos 'section' => 'configuracion'
        $this->render('configuracion', [
            'config' => $config,
            'section' => 'configuracion' 
        ]);
    }
    
    public function guardarConfiguracion() {
        // 1. Seguridad
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 2. Manejo de la Imagen (Logo)
            $ruta_logo = $_POST['logo_actual']; // Por defecto, mantenemos el que ya estaba

            // Si el usuario subió una imagen nueva...
            if (isset($_FILES['logo_imagen']) && $_FILES['logo_imagen']['error'] === UPLOAD_ERR_OK) {
                $nombre_archivo = $_FILES['logo_imagen']['name'];
                $tmp_name = $_FILES['logo_imagen']['tmp_name'];
                
                // Generamos un nombre único para evitar problemas de caché (ej: logo_174123.png)
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                $nuevo_nombre = 'logo_' . time() . '.' . $extension;
                
                // Ruta donde se guardará (carpeta public/assets/)
                $destino = __DIR__ . '/../../public/assets/' . $nuevo_nombre;
                
                if (move_uploaded_file($tmp_name, $destino)) {
                    $ruta_logo = 'assets/' . $nuevo_nombre; // Guardamos la ruta relativa para la BD
                }
            }

            // 3. Preparamos los datos para el Modelo
            $datos = [
                'primario' => $_POST['color_primario'],
                'secundario' => $_POST['color_secundario'],
                'titulo' => $_POST['titulo_landing'],
                'descripcion' => $_POST['descripcion_landing'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email_contacto'],
                'logo_url' => $ruta_logo // <--- Pasamos la ruta de la imagen
            ];
            
            // 4. Guardamos
            Configuracion::update($datos);
            
            // 5. Redirigimos
            header("Location: ?section=configuracion");
            exit();
        }
    }

    // --- USUARIOS ---
    public function usuarios() {
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
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }
        $this->render('usuarios_crear', ['section' => 'usuarios']);
    }

    public function guardarUsuario() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $datos = [
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'password' => $password_hash,
                'rol' => $_POST['rol'],
            ];
            Usuario::create($datos);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function restablecerConfiguracion() {
        // 1. Seguridad
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        // 2. Definimos los valores originales ("de fábrica")
        $datos_default = [
            'primario' => '#FF6B00',    // Naranja original
            'secundario' => '#FF8C42',  // Naranja secundario
            'titulo' => "Pollo Na'Guara",
            'descripcion' => 'La mejor calidad en pollos y aliños.',
            'telefono' => '0414-1234567',
            'email' => 'contacto@pollonaguara.com',
            'logo_url' => 'assets/logo.png' // Logo original
        ];
        
        // 3. Guardamos estos datos en la BD
        Configuracion::update($datos_default);
        
        // 4. Recargamos la página
        echo "<script>alert('¡Configuración restablecida a los valores originales!'); window.location.href='?section=configuracion';</script>";
        exit();
    }

    public function editarUsuario() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();
        if (isset($_GET['id'])) {
            $usuario = Usuario::find($_GET['id']);
            $this->render('usuarios_editar', ['section' => 'usuarios', 'usuario' => $usuario]);
        }
    }

    public function actualizarUsuario() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id' => $_POST['id'],
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
            ];
            Usuario::update($datos);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function eliminarUsuario() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();
        if (isset($_GET['id'])) {
            Usuario::delete($_GET['id']);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function cambiarEstadoUsuario() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            Usuario::cambiarEstado($_GET['id'], $_GET['estado']);
            header("Location: ?section=usuarios");
            exit();
        }
    }

    public function resetearClaveUsuario() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();
        if (isset($_GET['id'])) {
            $password_hash = password_hash("123456", PASSWORD_DEFAULT);
            Usuario::resetPassword($_GET['id'], $password_hash);
            echo "<script>alert('Contraseña restablecida a: 123456'); window.location.href='?section=usuarios';</script>";
            exit();
        }
    }

    // --- INVENTARIO ---
    public function inventario() {
        // SEGURIDAD: Empleados fuera
        if ($_SESSION['usuario_rol'] !== 'Administrador') {
            header("Location: ?section=stats");
            exit();
        }

        $filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
        $productos = Producto::all($filtroCategoria);
        
        // --- NUEVO: Obtenemos los totales para las cards ---
        $total_stock = Reporte::getStockTotal();
        $valor_inventario = Reporte::getValorInventario();
        
        $data = [
            'section' => 'inventario', 
            'lista_productos' => $productos,
            'filtro_actual' => $filtroCategoria,
            'total_stock' => $total_stock,          // <--- Dato para Card 1
            'valor_inventario' => $valor_inventario // <--- Dato para Card 2
        ];
        
        $this->render('inventario', $data);
    }

    public function crearProducto() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') { header("Location: ?section=stats"); exit(); }
        $this->render('productos_crear', ['section' => 'inventario']);
    }

    public function guardarProducto() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit(); // Bloqueo silencioso para POST

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
        if ($_SESSION['usuario_rol'] !== 'Administrador') { header("Location: ?section=stats"); exit(); }
        
        if (isset($_GET['id'])) {
            $producto = Producto::find($_GET['id']);
            $this->render('productos_editar', ['section' => 'inventario', 'producto' => $producto]);
        }
    }

    public function actualizarProducto() {
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();

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
        if ($_SESSION['usuario_rol'] !== 'Administrador') exit();

        if (isset($_GET['id'])) {
            Producto::delete($_GET['id']);
            header("Location: ?section=inventario");
            exit();
        }
    }

    // --- VENTAS ---
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
        $this->render('ventas_crear', ['section' => 'ventas', 'lista_productos' => $productos]);
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

            $datos_venta = ['cliente' => $cliente, 'vendedor' => $vendedor, 'total' => $total_venta];
            $item = ['id_producto' => $id_producto, 'cantidad' => $cantidad, 'precio_unitario' => $precio_unitario];

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
            $this->render('ventas_detalle', ['section' => 'ventas', 'venta' => $venta, 'detalles' => $detalles]);
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

    // --- REPORTES ---
    public function reportes() {
        $inicio = isset($_GET['inicio']) ? $_GET['inicio'] : null;
        $fin = isset($_GET['fin']) ? $_GET['fin'] : null;

        $data = [
            'section' => 'reportes',
            'top_productos' => Reporte::getTopProductos($inicio, $fin),
            'rendimiento_vendedores' => Reporte::getRendimientoVendedores($inicio, $fin),
            'metricas' => Reporte::getMetricasClave($inicio, $fin),
            'tabla_detalle' => Reporte::getDetalleVentas($inicio, $fin),
            'total_ventas' => Reporte::getTotalVentasPeriodo($inicio, $fin), // Nuevo cálculo directo
            'inicio' => $inicio, 
            'fin' => $fin
        ];

        $this->render('reportes', $data);
    }

    // --- PERFIL ---
    public function perfil() {
        $this->render('perfil', ['section' => 'perfil']);
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

            Usuario::resetPassword($_SESSION['usuario_id'], password_hash($pass1, PASSWORD_DEFAULT));
            echo "<script>alert('¡Contraseña actualizada!'); window.location.href='?section=stats';</script>";
            exit();
        }
    }

    // --- HELPER: RENDER ---
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
}