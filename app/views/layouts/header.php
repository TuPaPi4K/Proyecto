<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pollo Na'Guara</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="dashboard-header">
        <figure class="logo-container">
            <img src="assets/logo.png" alt="Pollo Na'Guara" class="logo" width="50" height="50">
            <figcaption class="empresa-nombre">
                <h1>Pollo Na'Guara</h1>
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