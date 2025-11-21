<section class="dashboard-section">
    <header class="section-header">
        <h2>Estadísticas Generales</h2>
    </header>
    
    <section class="stats-grid" aria-label="Estadísticas principales">
        <article class="stat-card">
            <h3>Ventas del Día</h3>
            <p class="stat-number">$<?php echo number_format($ventas_dia, 2); ?></p>
            <p><?php echo ($porcentaje_ayer > 0 ? '+' : '') . $porcentaje_ayer; ?>% vs ayer</p>
        </article>
        
        <article class="stat-card">
            <h3>Productos en Stock</h3>
            <p class="stat-number"><?php echo number_format($stock_total); ?></p>
            <p>85% disponibilidad</p>
        </article>
        
        <article class="stat-card">
            <h3>Usuarios Activos</h3>
            <p class="stat-number"><?php echo $usuarios_activos; ?></p>
            <p>En este momento</p>
        </article>
        
        <article class="stat-card">
            <h3>Órdenes Pendientes</h3>
            <p class="stat-number"><?php echo $ordenes_pendientes; ?></p>
            <p>Por procesar</p>
        </article>
    </section>
</section>