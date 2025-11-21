<nav class="dashboard-nav" aria-label="Navegación principal">
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="?section=stats" class="nav-link <?php echo ($section == 'stats') ? 'active' : ''; ?>">
                📊 Estadísticas
            </a>
        </li>
        <li class="nav-item">
            <a href="?section=usuarios" class="nav-link <?php echo ($section == 'usuarios') ? 'active' : ''; ?>">
                👥 Usuarios
            </a>
        </li>
        <li class="nav-item">
            <a href="?section=inventario" class="nav-link <?php echo ($section == 'inventario') ? 'active' : ''; ?>">
                📦 Inventario
            </a>
        </li>
        <li class="nav-item">
            <a href="?section=ventas" class="nav-link <?php echo ($section == 'ventas') ? 'active' : ''; ?>">
                💰 Ventas
            </a>
        </li>
        <li class="nav-item">
            <a href="?section=reportes" class="nav-link <?php echo ($section == 'reportes') ? 'active' : ''; ?>">
                📈 Reportes
            </a>
        </li>
    </ul>
</nav>
<main class="dashboard-main">