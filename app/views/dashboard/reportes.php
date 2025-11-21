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
            <h1>Reporte de Gestión - Pollo Na'Guara</h1>
            <p>Generado el: <?php echo date('d/m/Y H:i'); ?></p>
            <?php if($inicio && $fin): ?>
                <p>Período: <?php echo date('d/m/Y', strtotime($inicio)); ?> al <?php echo date('d/m/Y', strtotime($fin)); ?></p>
            <?php endif; ?>
        </div>

        <section class="stats-grid">
            <article class="stat-card">
                <h3>Ventas por Sucursal</h3>
                <?php if(empty($ventas_sucursal)): ?>
                    <p>No hay datos en este período.</p>
                <?php else: ?>
                    <?php foreach ($ventas_sucursal as $sucursal => $monto): ?>
                        <p><strong><?php echo $sucursal; ?>:</strong> $<?php echo number_format($monto, 2); ?></p>
                    <?php endforeach; ?>
                    <p style="border-top: 1px solid #eee; margin-top: 5px; padding-top: 5px;">
                        <strong>Total:</strong> $<?php echo number_format($total_ventas, 2); ?>
                    </p>
                <?php endif; ?>
            </article>
            
            <article class="stat-card">
                <h3>Top 5 Productos</h3>
                <?php if(empty($top_productos)): ?>
                    <p>No hay datos.</p>
                <?php else: ?>
                    <?php foreach ($top_productos as $producto => $cantidad): ?>
                        <p><strong><?php echo $producto; ?>:</strong> <?php echo $cantidad; ?> uds</p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </article>

            <article class="stat-card">
                <h3>Rendimiento Vendedores</h3>
                <?php if(empty($rendimiento_vendedores)): ?>
                    <p>No hay datos.</p>
                <?php else: ?>
                    <?php foreach ($rendimiento_vendedores as $vendedor => $venta): ?>
                        <p><strong><?php echo $vendedor; ?>:</strong> $<?php echo number_format($venta, 2); ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </article>

            <article class="stat-card">
                <h3>Métricas Clave</h3>
                <p><strong>Ticket Promedio:</strong> $<?php echo $metricas['ticket_promedio']; ?></p>
                <p><strong>Conversión:</strong> <?php echo $metricas['conversion']; ?></p>
                <p><strong>Crecimiento:</strong> <?php echo $metricas['crecimiento']; ?></p>
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
                    <th scope="col">Sucursal</th>
                    <th scope="col">Vendedor</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($tabla_detalle)): ?>
                    <tr><td colspan="6" style="text-align:center;">No se encontraron registros en este rango de fechas.</td></tr>
                <?php else: ?>
                    <?php foreach ($tabla_detalle as $fila): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($fila['fecha'])); ?></td>
                            <td><?php echo $fila['producto']; ?></td>
                            <td><?php echo $fila['cantidad']; ?></td>
                            <td>$<?php echo number_format($fila['total'], 2); ?></td>
                            <td><?php echo $fila['sucursal']; ?></td>
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