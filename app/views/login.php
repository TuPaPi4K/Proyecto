<?php
// 1. Cargamos configuración para colores
require_once __DIR__ . '/../Models/Configuracion.php';
$config = Configuracion::get();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $config['titulo_landing']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --naranja-primario: <?php echo $config['color_primario']; ?>;
            --naranja-secundario: <?php echo $config['color_secundario']; ?>;
        }

        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background: linear-gradient(135deg, var(--naranja-primario), var(--naranja-secundario)); 
            margin: 0; 
            font-family: 'Segoe UI', sans-serif; 
        }
        .login-card { 
            background: white; 
            padding: 2rem; 
            border-radius: 12px; 
            width: 100%; 
            max-width: 350px; 
            text-align: center; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); 
            position: relative;
        }
        .input-login { 
            width: 90%; 
            padding: 12px; 
            margin-bottom: 15px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            background: #f9f9f9;
        }
        .input-login:focus {
            border-color: var(--naranja-primario);
            outline: none;
            background: #fff;
        }
        .btn-login { 
            width: 98%; 
            padding: 12px; 
            background: var(--naranja-primario); 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 1rem;
            transition: transform 0.2s, opacity 0.2s;
        }
        .btn-login:hover { opacity: 0.9; transform: translateY(-1px); }
        .error { color: #D32F2F; margin-bottom: 10px; font-size: 0.9rem; background: #FFEBEE; padding: 5px; border-radius: 4px; }
        
        .opciones-extra {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 12px;
            font-size: 0.9rem;
        }
        .link-secundario {
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
        }
        .link-secundario:hover {
            color: var(--naranja-primario);
            text-decoration: underline;
        }
        .divider { border-top: 1px solid #eee; margin: 5px 0; }

        /* --- ESTILOS DEL MODAL --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
            animation: fadeIn 0.3s;
        }
        .modal-content {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 15px;
            text-align: center;
            max-width: 90%;
            width: 320px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            animation: slideUp 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            background: #E8F5E9;
            color: #2E7D32; /* Verde Éxito */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin: 0 auto 1.5rem auto;
        }
        .modal-title { margin: 0 0 10px 0; color: #333; }
        .modal-text { color: #666; margin-bottom: 20px; line-height: 1.5; }
        .role-badge { 
            background: #F3E5F5; color: #7B1FA2; 
            padding: 4px 10px; border-radius: 12px; font-weight: bold; font-size: 0.9rem;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px) scale(0.9); } to { transform: translateY(0) scale(1); } }
    </style>
</head>
<body>

    <div class="login-card">
        <h2 style="color: #333; margin-bottom: 1.5rem;"><?php echo $config['titulo_landing']; ?></h2>
        
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form action="?section=login-auth" method="POST">
            <input type="email" name="email" placeholder="Email" class="input-login" required>
            <input type="password" name="password" placeholder="Contraseña" class="input-login" required>
            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="opciones-extra">
            <a href="?section=registro" class="link-secundario" style="font-weight: bold; color: var(--naranja-primario);">
                ¿No tienes cuenta? Regístrate
            </a>
            
            <div class="divider"></div>

            <a href="?section=landing" class="link-secundario">
                ← Volver al Inicio
            </a>
        </div>
    </div>

    <?php if(isset($_GET['registro']) && $_GET['registro'] == 1): ?>
    <div id="modalExito" class="modal-overlay">
        <div class="modal-content">
            <div class="icon-circle">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 class="modal-title">¡Registro Exitoso!</h2>
            <p class="modal-text">
                El usuario ha sido creado correctamente.<br>
                <br>
                <span class="role-badge">Rol de Empleado Otorgado</span>
            </p>
            <button onclick="cerrarModal()" class="btn-login" style="margin-top: 10px;">
                Aceptar e Iniciar Sesión
            </button>
        </div>
    </div>
    
    <script>
        function cerrarModal() {
            // Ocultamos el modal
            document.getElementById('modalExito').style.display = 'none';
            // Limpiamos la URL para que si recarga no vuelva a salir
            window.history.replaceState({}, document.title, window.location.pathname + '?section=login');
        }
    </script>
    <?php endif; ?>

</body>
</html>