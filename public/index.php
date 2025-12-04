<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Iniciar sesión
session_start();

// 2. Cargar controladores y modelos necesarios
require_once '../app/Controllers/DashboardController.php';
require_once '../app/Controllers/AuthController.php';

// 3. Enrutador: ¿Qué sección quiere ver el usuario?
$section = isset($_GET['section']) ? $_GET['section'] : 'landing'; // <-- Si no pone nada, va a landing por defecto

// 4. SEGURIDAD: Lista de secciones que puede ver CUALQUIERA (sin loguearse)
// ¡AQUÍ ES DONDE ESTABA TU ERROR! Faltaba 'landing' en esta lista.
$rutas_publicas = ['landing', 'login', 'login-auth', 'registro', 'auth-registro'];

// El Portero: Si NO estás logueado Y la sección NO es pública...
if (!isset($_SESSION['usuario_id']) && !in_array($section, $rutas_publicas)) {
    // ... te mando a la landing (o al login si prefieres)
    header("Location: ?section=landing");
    exit();
}

$controller = new DashboardController();
$auth = new AuthController();

switch ($section) {
    // --- RUTAS PÚBLICAS ---
    case 'landing':
        // Carga directa de la vista landing
        require_once '../app/Views/landing.php';
        break;

    case 'login':
        $auth->login();
        break;
    case 'login-auth':
        $auth->authenticate();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'registro':
        $auth->registro();
        break;
    case 'auth-registro':
        $auth->registrarUsuario();
        break;

    // --- RUTAS PRIVADAS (Dashboard) ---
    case 'stats':
        $controller->stats();
        break;
        
    // --- CONFIGURACIÓN ---
    case 'configuracion':
        $controller->configuracion();
        break;
    case 'config-guardar':
        $controller->guardarConfiguracion();
        break;
    case 'config-reset':
        $controller->restablecerConfiguracion();
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
    case 'usuarios-reset':
        $controller->resetearClaveUsuario();
        break;

    // --- PRODUCTOS ---
    case 'inventario':
        $controller->inventario();
        break;
    case 'productos-crear':
        $controller->crearProducto();
        break;
    case 'productos-guardar':
        $controller->guardarProducto();
        break;
    case 'productos-editar':
        $controller->editarProducto();
        break;
    case 'productos-actualizar':
        $controller->actualizarProducto();
        break;
    case 'productos-eliminar':
        $controller->eliminarProducto();
        break;

    // --- VENTAS ---
    case 'ventas':
        $controller->ventas();
        break;
    case 'ventas-crear':
        $controller->crearVenta();
        break;
    case 'ventas-guardar':
        $controller->guardarVenta();
        break;
    case 'ventas-ver': 
        $controller->verVenta(); 
        break;
    case 'ventas-procesar': 
        $controller->procesarVenta(); 
        break;
    case 'ventas-cancelar': 
        $controller->cancelarVenta(); 
        break;

    // --- OTROS ---
    case 'reportes':
        $controller->reportes();
        break;
    case 'perfil':
        $controller->perfil();
        break;
    case 'perfil-guardar':
        $controller->guardarPerfil();
        break;
        
    default:
        // Si la ruta no existe, mandamos a landing
        header("Location: ?section=landing");
        break;
}