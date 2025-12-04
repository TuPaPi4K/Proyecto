<section class="dashboard-section">
    <header class="section-header">
        <h2>Reportes y Estadísticas</h2>
        <button onclick="window.print()" class="btn-primary no-print">🖨️ Imprimir Reporte</button>
    </header>
    
    <section class="filtros no-print">
        <form action="" method="GET" style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
            <input type="hidden" name="section" value="reportes">
            
            <label>Desde:</label>
            <input type="date" name="inicio" value="<?php echo $inicio ?? ''; ?>" class="input-fecha">
            
            <label>Hasta:</label>
            <input type="date" name="fin" value="<?php echo $fin ?? ''; ?>" class="input-fecha">
            
            <button type="submit" class="btn-primary">Aplicar Filtros</button>
            
            <?php if(isset($inicio) && $inicio != ''): ?>
                <a href="?section=reportes" class="btn-eliminar" style="text-decoration:none;">Limpiar</a>
            <?php endif; ?>
        </form>
    </section>
    
    <div class="print-area">
        <div class="print-header" style="display:none; margin-bottom:20px; text-align:center;">
            <img src="<?php echo $config['logo_url']; ?>" style="height: 60px; width: auto; margin-bottom: 10px;">
            <h1 style="margin:0;"><?php echo $config['titulo_landing']; ?></h1>
            <p style="margin:5px 0; font-size: 0.9rem;"><?php echo $config['email_contacto']; ?> | <?php echo $config['telefono']; ?></p>
            <hr style="margin: 15px 0; border: 0; border-top: 1px solid #ccc;">
            
            <h2 style="margin-top:0;">Reporte de Gestión</h2>
            <p>Generado el: <?php echo date('d/m/Y H:i'); ?></p>
            <?php if($inicio && $fin): ?>
                <p>Período: <?php echo date('d/m/Y', strtotime($inicio)); ?> al <?php echo date('d/m/Y', strtotime($fin)); ?></p>
            <?php endif; ?>
        </div>

        <section class="stats-grid">
            
            <article class="stat-card">
                <h3> Rendimiento Empleados</h3>
                <?php if(empty($rendimiento_vendedores)): ?>
                    <p>No hay datos.</p>
                <?php else: ?>
                    <?php foreach ($rendimiento_vendedores as $vendedor => $venta): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                            <span><?php echo $vendedor; ?></span>
                            <strong>$<?php echo number_format($venta, 2); ?></strong>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </article>

            <article class="stat-card">
                <h3> Top 5 Productos</h3>
                <?php if(empty($top_productos)): ?>
                    <p>No hay datos.</p>
                <?php else: ?>
                    <?php foreach ($top_productos as $producto => $cantidad): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                            <span><?php echo $producto; ?></span>
                            <strong><?php echo $cantidad; ?> uds</strong>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </article>

        </section>
        
        <h3 style="margin: 2rem 0 1rem 0;">Detalle de Movimientos</h3>
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Total</th>
                    <th scope="col">Vendedor</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($tabla_detalle)): ?>
                    <tr><td colspan="5" style="text-align:center;">No se encontraron registros en este rango de fechas.</td></tr>
                <?php else: ?>
                    <?php foreach ($tabla_detalle as $fila): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($fila['fecha'])); ?></td>
                            <td><?php echo $fila['producto']; ?></td>
                            <td><?php echo $fila['cantidad']; ?></td>
                            <td>$<?php echo number_format($fila['total'], 2); ?></td>
                            <td><?php echo $fila['vendedor']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<style>
    @media print {
        /* Ocultamos menú, filtros y botones */
        .dashboard-nav, .dashboard-header, .no-print, button {
            display: none !important;
        }
        /* Ajustamos el layout para papel */
        .dashboard-main { margin: 0; padding: 0; }
        .dashboard-section { box-shadow: none; border: none; }
        .print-header { display: block !important; }
        body { background: white; color: black; }
        .stat-card { border: 1px solid #ccc; break-inside: avoid; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    }
</style>