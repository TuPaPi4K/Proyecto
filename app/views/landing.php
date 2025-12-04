<?php
require_once __DIR__ . '/../Models/Configuracion.php';
$config = Configuracion::get();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['titulo_landing']; ?></title>
    <link rel="stylesheet" href="public/css/styles.css"> <style>
        /* Inyección de colores dinámicos */
        :root {
            --naranja-primario: <?php echo $config['color_primario']; ?>;
            --naranja-secundario: <?php echo $config['color_secundario']; ?>;
        }
        
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #fff; }
        
        .hero {
            background: linear-gradient(135deg, var(--naranja-primario), var(--naranja-secundario));
            color: white;
            padding: 4rem 2rem;
            text-align: center;
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .hero h1 { font-size: 3.5rem; margin-bottom: 1rem; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .hero p { font-size: 1.5rem; max-width: 600px; margin-bottom: 2rem; opacity: 0.9; }
        
        .btn-landing {
            background: white;
            color: var(--naranja-primario);
            padding: 1rem 2rem;
            text-decoration: none;
            font-weight: bold;
            border-radius: 50px;
            font-size: 1.2rem;
            transition: transform 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: inline-block;
            margin: 0 10px;
        }
        .btn-landing:hover { transform: scale(1.05); }
        .btn-outline { background: transparent; border: 2px solid white; color: white; }
        
        .info-section { padding: 4rem 2rem; text-align: center; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto; }
        .info-card { padding: 2rem; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .info-card h3 { color: var(--naranja-primario); margin-bottom: 1rem; }

        /* RESPONSIVE LANDING */
        @media (max-width: 768px) {
            .hero-section {
                padding: 3rem 1rem;
                min-height: auto;
            }
            
            .hero-section h1 {
                font-size: 2rem; /* Texto más pequeño */
                line-height: 1.2;
            }
            
            .hero-section p {
                font-size: 1.1rem;
                padding: 0 1rem;
            }
            
            /* Botones uno debajo del otro */
            .hero-section div {
                flex-direction: column;
                width: 100%;
                gap: 1rem;
            }
            
            .btn-blanco {
                width: 100%;
                display: block;
                box-sizing: border-box; /* Para que el padding no rompa el ancho */
            }
            
            .info-section {
                padding: 2rem 1rem;
                grid-template-columns: 1fr; /* Una sola columna */
            }
        }
    </style>
</head>
<body>

    <section class="hero">
        <img src="<?php echo $config['logo_url']; ?>" alt="Logo" style="width: 150px; height: auto; margin-bottom: 20px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 50%;">
        
        <h1><?php echo $config['titulo_landing']; ?></h1>
        <p><?php echo $config['descripcion_landing']; ?></p>
        
        <div>
            <a href="?section=login" class="btn-landing">Iniciar Sesión</a>
            <a href="?section=registro" class="btn-landing btn-outline">Registrarse</a>
        </div>
    </section>

    <section class="info-section">
        <div class="info-card">
            <h3>📍 Ubicación</h3>
            <p>Nos ubicamos en Ruiz Pineda 1, Calle 1, Final de la Avenida los Horcones.</p>
        </div>
        <div class="info-card">
            <h3>📞 Contáctanos</h3>
            <p>Tel: <?php echo $config['telefono']; ?></p>
            <p>Email: <?php echo $config['email_contacto']; ?></p>
        </div>
        <div class="info-card">
            <h3>📦 Productos Frescos</h3>
            <p>Calidad garantizada del campo a tu mesa.</p>
        </div>
    </section>

    <footer style="text-align:center; padding: 2rem; background: #333; color: white;">
        &copy; <?php echo date('Y'); ?> <?php echo $config['titulo_landing']; ?> - Todos los derechos reservados.
    </footer>

</body>
</html>