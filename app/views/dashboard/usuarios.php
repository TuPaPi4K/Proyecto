<section class="dashboard-section">
    <header class="section-header">
        <h2>Gestión de Usuarios</h2>
        <a href="?section=usuarios-crear" class="btn-primary" style="text-decoration:none; display:inline-block;">+ Nuevo Usuario</a>
    </header>
    
    <section class="filtros">
        <form action="" method="GET" style="display:flex; gap:1rem; width:100%;">
            <input type="hidden" name="section" value="usuarios">
            <input type="text" placeholder="Buscar usuario..." class="input-busqueda" aria-label="Buscar usuarios">
            
            <select name="rol" class="select-filtro" aria-label="Filtrar por rol" onchange="this.form.submit()">
                <option <?php echo (!isset($filtro_actual) || $filtro_actual == 'Todos los roles') ? 'selected' : ''; ?>>Todos los roles</option>
                <option value="Administrador" <?php echo (isset($filtro_actual) && $filtro_actual == 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                <option value="Empleado" <?php echo (isset($filtro_actual) && $filtro_actual == 'Empleado') ? 'selected' : ''; ?>>Empleado</option>
            </select>
        </form>
    </section>
    
    <table class="tabla-datos">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Rol</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_usuarios as $usuario): ?>
                <?php 
                    // Lógica robusta: Funciona con "Activo", "1", 1, true, etc.
                    $es_activo = ($usuario['estado'] === 'Activo' || $usuario['estado'] == 1);
                    
                    // Convertimos el rol a minúsculas para las clases CSS (ej: empleado)
                    $clase_rol = strtolower($usuario['rol']); 
                    $clase_estado = $es_activo ? 'activo' : 'inactivo';
                    $texto_estado = $es_activo ? 'Activo' : 'Inactivo';
                ?>
                <tr>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td>
                        <mark class="badge <?php echo $clase_rol; ?>">
                            <?php echo $usuario['rol']; ?>
                        </mark>
                    </td>
                    
                    <td>
                        <mark class="estado <?php echo $clase_estado; ?>">
                            <?php echo $texto_estado; ?>
                        </mark>
                    </td>
                    <td>
                        <menu class="acciones" style="display:flex; gap: 5px;">
                            <a href="?section=usuarios-editar&id=<?php echo $usuario['id']; ?>" class="btn-editar" style="text-decoration:none;">Editar</a>

                            <?php if ($es_activo): ?>
                                <a href="?section=usuarios-estado&id=<?php echo $usuario['id']; ?>&estado=0" 
                                   class="btn-eliminar" 
                                   style="text-decoration:none;"
                                   onclick="return confirm('¿Estás seguro de ELIMINAR este usuario? Pasará a estado Inactivo.');">
                                   Eliminar
                                </a>
                            <?php else: ?>
                                <a href="?section=usuarios-estado&id=<?php echo $usuario['id']; ?>&estado=1" 
                                   class="btn-activar" 
                                   style="text-decoration:none;">
                                   Activar
                                </a>
                            <?php endif; ?>

                            <a href="?section=usuarios-reset&id=<?php echo $usuario['id']; ?>" 
                               class="btn-editar" 
                               style="text-decoration:none; background-color:#FF9800; color:white; border:none;"
                             onclick="return confirm('¿Restablecer la contraseña de este usuario a 123456?');"
                             title="Restablecer clave a 123456">
                             🔑
                            </a>
                        </menu>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>