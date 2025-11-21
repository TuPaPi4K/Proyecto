<section class="dashboard-section">
    <header class="section-header">
        <h2>Reportes y Estadísticas</h2>
        <button class="btn-primary">Generar Reporte</button>
    </header>
    
    <section class="filtros">
        <select class="select-filtro" aria-label="Tipo de reporte">
            <option>Ventas por Sucursal</option>
            <option>Productos Más Vendidos</option>
            <option>Rendimiento por Vendedor</option>
            <option>Inventario por Categoría</option>
        </select>
        <input type="date" class="input-fecha" aria-label="Fecha inicial">
        <input type="date" class="input-fecha" aria-label="Fecha final">
        <button class="btn-primary">Aplicar Filtros</button>
    </section>
    
    <section class="stats-grid">
        <article class="stat-card">
            <h3>Ventas por Sucursal</h3>
            <?php foreach ($ventas_sucursal as $sucursal => $monto): ?>
                <p><strong><?php echo $sucursal; ?>:</strong> $<?php echo number_format($monto); ?></p>
            <?php endforeach; ?>
            <p style="border-top: 1px solid #eee; margin-top: 5px; padding-top: 5px;">
                <strong>Total:</strong> $<?php echo number_format($total_ventas); ?>
            </p>
        </article>
        
        <article class="stat-card">
            <h3>Top 5 Productos</h3>
            <?php foreach ($top_productos as $producto => $cantidad): ?>
                <p><strong><?php echo $producto; ?>:</strong> <?php echo $cantidad; ?> uds</p>
            <?php endforeach; ?>
        </article>

        <article class="stat-card">
            <h3>Rendimiento Vendedores</h3>
            <?php foreach ($rendimiento_vendedores as $vendedor => $venta): ?>
                <p><strong><?php echo $vendedor; ?>:</strong> $<?php echo number_format($venta); ?></p>
            <?php endforeach; ?>
        </article>

        <article class="stat-card">
            <h3>Métricas Clave</h3>
            <p><strong>Ticket Promedio:</strong> $<?php echo $metricas['ticket_promedio']; ?></p>
            <p><strong>Conversión:</strong> <?php echo $metricas['conversion']; ?></p>
            <p><strong>Clientes Recurrentes:</strong> <?php echo $metricas['recurrentes']; ?></p>
            <p><strong>Crecimiento Mensual:</strong> <?php echo $metricas['crecimiento']; ?></p>
        </article>
    </section>
    
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
            <?php foreach ($tabla_detalle as $fila): ?>
                <tr>
                    <td><?php echo $fila['fecha']; ?></td>
                    <td><?php echo $fila['producto']; ?></td>
                    <td><?php echo $fila['cantidad']; ?></td>
                    <td>$<?php echo number_format($fila['total'], 2); ?></td>
                    <td><?php echo $fila['sucursal']; ?></td>
                    <td><?php echo $fila['vendedor']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>