<section class="dashboard-section">
    <header class="section-header">
        <h2>Gestión de Ventas</h2>
        <button class="btn-primary">+ Nueva Venta</button>
    </header>
    
    <section class="resumen-ventas" aria-label="Resumen de ventas">
        <article class="stat-card">
            <h3>Ventas Hoy</h3>
            <p class="stat-number">$<?php echo number_format($ventas_hoy_total, 2); ?></p>
            <p><?php echo $ventas_hoy_cantidad; ?> transacciones</p>
        </article>
        <article class="stat-card">
            <h3>Ventas Semana</h3>
            <p class="stat-number">$<?php echo number_format($ventas_semana, 2); ?></p>
            <p>+15% vs semana anterior</p>
        </article>
        <article class="stat-card">
            <h3>Ticket Promedio</h3>
            <p class="stat-number">$<?php echo number_format($ticket_promedio, 2); ?></p>
            <p>+8% vs mes anterior</p>
        </article>
    </section>
    
    <table class="tabla-datos">
        <thead>
            <tr>
                <th scope="col">ID Venta</th>
                <th scope="col">Fecha</th>
                <th scope="col">Cliente</th>
                <th scope="col">Vendedor</th>
                <th scope="col">Total</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_ventas as $venta): ?>
                <?php 
                    // Lógica visual: Convertir estado 'Completada' a clase 'completada' (minúsculas)
                    $clase_estado = strtolower($venta['estado']); 
                ?>
                <tr>
                    <td><?php echo $venta['id_venta']; ?></td>
                    <td><?php echo $venta['fecha']; ?></td>
                    <td><?php echo $venta['cliente']; ?></td>
                    <td><?php echo $venta['vendedor']; ?></td>
                    <td>$<?php echo number_format($venta['total'], 2); ?></td>
                    <td>
                        <mark class="estado <?php echo $clase_estado; ?>">
                            <?php echo $venta['estado']; ?>
                        </mark>
                    </td>
                    <td>
                        <menu class="acciones">
                            <?php if ($venta['estado'] === 'Pendiente'): ?>
                                <button class="btn-activar">Procesar</button>
                                <button class="btn-eliminar">Cancelar</button>
                            <?php else: ?>
                                <button class="btn-editar">Ver Detalle</button>
                                <button class="btn-eliminar">Imprimir</button>
                            <?php endif; ?>
                        </menu>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>