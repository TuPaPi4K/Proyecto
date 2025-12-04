<?php
// 1. Cargamos configuración para los colores
require_once __DIR__ . '/../Models/Configuracion.php';
$config = Configuracion::get();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - <?php echo $config['titulo_landing']; ?></title>
    <style>
        /* Variables CSS dinámicas */
        :root {
            --naranja-primario: <?php echo $config['color_primario']; ?>;
            --naranja-secundario: <?php echo $config['color_secundario']; ?>;
        }

        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            /* Fondo con degradado */
            background: linear-gradient(135deg, var(--naranja-primario), var(--naranja-secundario)); 
            margin: 0; 
            font-family: sans-serif; 
        }
        .login-card { 
            background: white; 
            padding: 2rem; 
            border-radius: 8px; 
            width: 100%; 
            max-width: 350px; 
            text-align: center; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.2); 
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .input-login { 
            width: 90%; 
            padding: 10px; 
            margin-bottom: 15px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
        .btn-login { 
            width: 96%; 
            padding: 10px; 
            background: var(--naranja-primario); 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 1rem;
        }
        .btn-login:hover { opacity: 0.9; }
        .error { color: red; font-size: 0.9rem; }
        
        /* Estilos para los enlaces inferiores */
        .enlaces-inferiores {
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .link-secundario {
            text-decoration: none;
            color: #666;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .link-secundario:hover {
            color: var(--naranja-primario);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 style="color: #333; margin: 0;">Crear Cuenta</h2>
        
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form action="?section=auth-registro" method="POST">
            <input type="text" name="nombre" placeholder="Nombre Completo" class="input-login" required>
            <input type="email" name="email" placeholder="Email" class="input-login" required>
            <input type="password" name="password" placeholder="Contraseña" class="input-login" required>
            
            <button type="submit" class="btn-login">Registrarse</button>
        </form>

        <div class="enlaces-inferiores">
            <a href="?section=login" class="link-secundario" style="color: var(--naranja-primario); font-weight: bold;">
                ¿Ya tienes cuenta? Inicia Sesión
            </a>
            
            <a href="?section=landing" class="link-secundario">
                ← Volver al Inicio
            </a>
        </div>
    </div>
</body>
</html>