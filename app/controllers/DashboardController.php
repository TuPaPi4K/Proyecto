<?php

require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Producto.php';
require_once __DIR__ . '/../Models/Venta.php';
require_once __DIR__ . '/../Models/Reporte.php';

class DashboardController {
    
    // --- SECCIÓN DE ESTADÍSTICAS ---
    public function stats() {
        $data = [
            'section' => 'stats',
            'ventas_dia' => 4250.00,
            'porcentaje_ayer' => 12,
            'stock_total' => 1248,
            'usuarios_activos' => 24,
            'ordenes_pendientes' => 18
        ];

        $this->render('stats', $data);
    }

    // --- SECCIÓN DE INVENTARIO---
    public function inventario() {
        $productos = Producto::all();
        
        $data = [
            'section' => 'inventario',
            'lista_productos' => $productos
        ];

        $this->render('inventario', $data);
    }

    // --- SECCIÓN DE USUARIOS ---
   public function usuarios() {
        // 1. Verificamos si hay un filtro en la URL
        $filtroRol = isset($_GET['rol']) ? $_GET['rol'] : null;

        // 2. Pedimos los datos al modelo (pasando el filtro)
        $usuarios = Usuario::all($filtroRol);
        
        $data = [
            'section' => 'usuarios', 
            'lista_usuarios' => $usuarios,
            'filtro_actual' => $filtroRol // Pasamos esto para mantener el select seleccionado
        ];

        $this->render('usuarios', $data);
    }

    // --- SECCIÓN DE VENTAS ---
    public function ventas() {
        // Obtenemos la lista para la tabla
        $lista_ventas = Venta::all();
        
        // Preparamos los datos (incluyendo las métricas de las tarjetas)
        $data = [
            'section' => 'ventas',
            'lista_ventas' => $lista_ventas,
            
            // Datos para las tarjetas de arriba (Simulados)
            'ventas_hoy_total' => 4250.00,
            'ventas_hoy_cantidad' => 32,
            'ventas_semana' => 28450.00,
            'ticket_promedio' => 132.81
        ];

        $this->render('ventas', $data);
    }

    // --- SECCIÓN DE REPORTES (NUEVA) ---
    public function reportes() {
        // Recopilamos datos de múltiples fuentes del modelo
        $ventasSucursal = Reporte::getVentasPorSucursal();
        $topProductos = Reporte::getTopProductos();
        $rendimiento = Reporte::getRendimientoVendedores();
        $metricas = Reporte::getMetricasClave();
        $detalleVentas = Reporte::getDetalleVentas();

        // Calculamos el total general de ventas sumando las sucursales
        $totalVentas = array_sum($ventasSucursal);

        $data = [
            'section' => 'reportes',
            'ventas_sucursal' => $ventasSucursal,
            'total_ventas' => $totalVentas,
            'top_productos' => $topProductos,
            'rendimiento_vendedores' => $rendimiento,
            'metricas' => $metricas,
            'tabla_detalle' => $detalleVentas
        ];

        $this->render('reportes', $data);
    }

// 1. Muestra el formulario
    public function crearUsuario() {
        $data = ['section' => 'usuarios'];
        $this->render('usuarios_crear', $data);
    }

    // 2. Guarda los datos
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

    // 3. Cargar formulario de edición con datos
    public function editarUsuario() {
        // Verificamos que venga el ID en la URL
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $usuario = Usuario::find($id);
            
            $data = [
                'section' => 'usuarios',
                'usuario' => $usuario // Pasamos los datos del usuario a la vista
            ];
            
            $this->render('usuarios_editar', $data);
        } else {
            // Si no hay ID, mandamos de vuelta a la lista
            header("Location: ?section=usuarios");
        }
    }

    // 4. Procesar la actualización
    public function actualizarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id' => $_POST['id'], // ¡Importante! El ID viene oculto
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

    // 5. Procesar la eliminación
    public function eliminarUsuario() {
        // Solo eliminamos si viene un ID en la URL
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            Usuario::delete($id);
            
            // Redirigimos a la lista
            header("Location: ?section=usuarios");
            exit();
        }
    }

    // 6. Procesar cambio de estado
    public function cambiarEstadoUsuario() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id = $_GET['id'];
            $estado = $_GET['estado']; // Vendrá como 1 o 0
            
            Usuario::cambiarEstado($id, $estado);
            
            header("Location: ?section=usuarios");
            exit();
        }
    }

    // --- HELPER PARA RENDERIZAR VISTAS ---
    private function render($viewName, $data = []) {
        // Convierte las claves del array en variables
        extract($data);

        $viewsPath = __DIR__ . '/../Views/';

        require_once $viewsPath . 'layouts/header.php';
        require_once $viewsPath . 'layouts/navigation.php';
        require_once $viewsPath . "dashboard/$viewName.php";
        require_once $viewsPath . 'layouts/footer.php';
    }

    // --- PRODUCTOS ---
    
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

    // 3. Formulario de Edición
    public function editarProducto() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $producto = Producto::find($id);
            
            $data = [
                'section' => 'inventario',
                'producto' => $producto
            ];
            
            $this->render('productos_editar', $data);
        }
    }

    // 4. Procesar Actualización
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

    // 5. Eliminar
    public function eliminarProducto() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            Producto::delete($id);
            header("Location: ?section=inventario");
            exit();
        }
    }

}