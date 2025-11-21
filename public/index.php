<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Iniciar sesión
session_start();

// 2. Cargar controladores
require_once '../app/Controllers/DashboardController.php';
require_once '../app/Controllers/AuthController.php';

// 3. Enrutador
$section = isset($_GET['section']) ? $_GET['section'] : 'login';

// 4. SEGURIDAD: Si no estás logueado, vas para afuera (Login)
$rutas_publicas = ['login', 'login-auth', 'generar-pass'];

if (!isset($_SESSION['usuario_id']) && !in_array($section, $rutas_publicas)) {
    header("Location: ?section=login");
    exit();
}

$controller = new DashboardController();
$auth = new AuthController();

switch ($section) {
    // --- AUTH ---
    case 'login':
        $auth->login();
        break;
    case 'login-auth':
        $auth->authenticate();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'generar-pass':
        // Herramienta temporal para obtener hash de contraseña
        echo password_hash("123456", PASSWORD_DEFAULT);
        break;

    // --- DASHBOARD ---
    case 'stats':
        $controller->stats();
        break;
        
    // --- USUARIOS ---
    case 'usuarios':
        $controller->usuarios();
        break;
    case 'usuarios-crear':
        $controller->crearUsuario();
        break;
    case 'usuarios-guardar':
        $controller->guardarUsuario();
        break;
    case 'usuarios-editar':
        $controller->editarUsuario();
        break;
    case 'usuarios-actualizar':
        $controller->actualizarUsuario();
        break;
    case 'usuarios-eliminar':
        $controller->eliminarUsuario();
        break;
    case 'usuarios-estado':
        $controller->cambiarEstadoUsuario();
        break;

// --- PRODUCTOS / INVENTARIO ---
    case 'inventario':
        $controller->inventario();
        break;
    case 'productos-crear':       // <--- NUEVA
        $controller->crearProducto();
        break;
    case 'productos-guardar':     // <--- NUEVA
        $controller->guardarProducto();
        break;
    case 'inventario':
        $controller->inventario();
        break;
        case 'productos-editar':      // <--- NUEVA
        $controller->editarProducto();
        break;
    case 'productos-actualizar':  // <--- NUEVA
        $controller->actualizarProducto();
        break;
    case 'productos-eliminar':    // <--- NUEVA
        $controller->eliminarProducto();
        break;


    case 'ventas':
        $controller->ventas();
        break;
    case 'reportes':
        $controller->reportes();
        break;
        
    default:
        $controller->stats();
        break;
}