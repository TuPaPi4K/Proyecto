<nav class="dashboard-nav" aria-label="Navegación principal">
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="?section=stats" class="nav-link <?php echo ($section == 'stats') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i> Estadísticas
            </a>
        </li>

        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'Administrador'): ?>
            <li class="nav-item">
                <a href="?section=usuarios" class="nav-link <?php echo ($section == 'usuarios') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i> Usuarios
                </a>
            </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'Administrador'): ?>
            <li class="nav-item">
                <a href="?section=inventario" class="nav-link <?php echo ($section == 'inventario') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-boxes-stacked"></i> Inventario
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="?section=ventas" class="nav-link <?php echo ($section == 'ventas') ? 'active' : ''; ?>">
                <i class="fa-solid fa-cart-shopping"></i> Ventas
            </a>
        </li>

        <li class="nav-item">
            <a href="?section=reportes" class="nav-link <?php echo ($section == 'reportes') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i> Reportes
            </a>
        </li>

        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'Administrador'): ?>
            <li class="nav-item">
                <a href="?section=configuracion" class="nav-link <?php echo ($section == 'configuracion') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gear"></i> Configuración
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<main class="dashboard-main">