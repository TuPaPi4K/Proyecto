<section class="dashboard-section">
    <header class="section-header">
        <h2>Gestión de Ventas</h2>
        <a href="?section=ventas-crear" class="btn-primary" style="text-decoration:none;">+ Nueva Venta</a>
    </header>
    
    <section class="resumen-ventas">
        
        <article class="stat-card">
            <h3>Ventas Hoy</h3>
            <p class="stat-number">$<?php echo number_format($ventas_hoy_total, 2); ?></p>
            <p><?php echo $ventas_hoy_cantidad; ?> transacciones</p>
        </article>
        
        <article class="stat-card">
            <h3>Ventas Semana</h3>
            <p class="stat-number">$<?php echo number_format($ventas_semana, 2); ?></p>
            <p>Últimos 7 días</p>
        </article>
        
        <article class="stat-card">
            <h3>Ticket Promedio</h3>
            <p class="stat-number">$<?php echo number_format($ticket_promedio, 2); ?></p>
            <p>Histórico global</p>
        </article>
        
    </section>
    
    <section class="filtros">
        <form action="" method="GET" style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
            <input type="hidden" name="section" value="ventas">
            
            <label>Desde:</label>
            <input type="date" name="inicio" value="<?php echo $inicio ?? ''; ?>" class="input-fecha">
            
            <label>Hasta:</label>
            <input type="date" name="fin" value="<?php echo $fin ?? ''; ?>" class="input-fecha">
            
            <button type="submit" class="btn-primary">Filtrar</button>
            
            <?php if(isset($inicio)): ?>
                <a href="?section=ventas" class="btn-eliminar" style="text-decoration:none;">Limpiar</a>
            <?php endif; ?>
        </form>
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
                    $clase_estado = strtolower($venta['estado']); 
                ?>
                <tr>
                    <td><?php echo $venta['id_venta']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?></td>
                    <td><?php echo $venta['cliente']; ?></td>
                    <td><?php echo $venta['vendedor']; ?></td>
                    <td>$<?php echo number_format($venta['total'], 2); ?></td>
                    <td>
                        <mark class="estado <?php echo $clase_estado; ?>">
                            <?php echo $venta['estado']; ?>
                        </mark>
                    </td>
                    <td>
                        <menu class="acciones" style="display:flex; gap: 5px;">
                            
                            <a href="?section=ventas-ver&id=<?php echo $venta['id']; ?>" class="btn-editar" style="text-decoration:none;">Ver / Imprimir</a>

                            <?php if ($venta['estado'] === 'Pendiente'): ?>
                                <a href="?section=ventas-procesar&id=<?php echo $venta['id']; ?>" class="btn-activar" style="text-decoration:none;">Procesar</a>
                                <a href="?section=ventas-cancelar&id=<?php echo $venta['id']; ?>" class="btn-eliminar" style="text-decoration:none;" onclick="return confirm('¿Cancelar venta? El stock será devuelto.');">Cancelar</a>
                            <?php elseif ($venta['estado'] === 'Completada'): ?>
                                <a href="?section=ventas-cancelar&id=<?php echo $venta['id']; ?>" class="btn-eliminar" style="text-decoration:none;" onclick="return confirm('¿ANULAR esta venta? El stock volverá al inventario.');">Anular</a>
                            <?php endif; ?>

                        </menu>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>