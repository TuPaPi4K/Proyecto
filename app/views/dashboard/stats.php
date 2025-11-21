<section class="dashboard-section">
    <header class="section-header">
        <h2>Tablero Principal</h2>
        <p style="color: #666;">Resumen de actividad en tiempo real</p>
    </header>
    
    <section class="stats-grid" aria-label="Estadísticas principales">
        <article class="stat-card">
            <h3>Ventas del Día</h3>
            <p class="stat-number">$<?php echo number_format($ventas_dia, 2); ?></p>
            <p><?php echo $transacciones_hoy; ?> transacciones hoy</p>
        </article>
        
        <article class="stat-card">
            <h3>Inventario Total</h3>
            <p class="stat-number"><?php echo number_format($stock_total); ?></p>
            <p>Unidades en stock</p>
        </article>
        
        <article class="stat-card">
            <h3>Personal Habilitado</h3> 
            <p class="stat-number"><?php echo $usuarios_activos; ?></p>
            <p>Cuentas con acceso</p>
        </article>
        
        <article class="stat-card" style="border-left-color: #2E7D32;">
            <h3>Estado del Sistema</h3>
            <p class="stat-number" style="color: #2E7D32; font-size: 1.5rem;">ONLINE</p>
            <p>Base de datos conectada</p>
        </article>
    </section>
    
    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <a href="?section=ventas-crear" class="btn-primary" style="text-decoration: none; text-align: center; flex: 1;">
            💰 Nueva Venta Rápida
        </a>
        <a href="?section=productos-crear" class="btn-editar" style="text-decoration: none; text-align: center; flex: 1; display: flex; align-items: center; justify-content: center;">
            📦 Agregar Producto
        </a>
    </div>
</section>