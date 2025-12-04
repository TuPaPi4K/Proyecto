<?php
// 1. Cargamos la configuración (Colores y Títulos)
// Usamos __DIR__ para asegurar que encuentre el archivo sin importar desde dónde se llame
require_once __DIR__ . '/../../models/Configuracion.php';
$config = Configuracion::get();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo $config['titulo_landing']; ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/styles.css">

    <style>
        :root {
            --naranja-primario: <?php echo $config['color_primario']; ?> !important;
            --naranja-secundario: <?php echo $config['color_secundario']; ?> !important;
            --naranja-claro: <?php echo $config['color_primario']; ?>15 !important; 
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--naranja-primario), var(--naranja-secundario)) !important;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <figure class="logo-container">
            <img src="<?php echo $config['logo_url']; ?>" alt="Logo" class="logo" width="50" height="50">
            <figcaption class="empresa-nombre">
                <h1><?php echo $config['titulo_landing']; ?></h1>
            </figcaption>
        </figure>

        <div class="user-profile">
            <div class="user-info">
                <span class="user-name">Hola, <?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?></span>
                <span class="user-role-badge"><?php echo $_SESSION['usuario_rol'] ?? 'Invitado'; ?></span>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <a href="?section=perfil" class="btn-logout" title="Cambiar mi clave">
                    Mi Perfil
                </a>

                <a href="?section=logout" class="btn-logout" title="Salir del sistema">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </header>