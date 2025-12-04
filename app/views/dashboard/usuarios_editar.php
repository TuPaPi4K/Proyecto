<section class="dashboard-section">
    <header class="section-header">
        <h2>Editar Usuario: <?php echo $usuario['nombre']; ?></h2>
        <a href="?section=usuarios" class="btn-eliminar">Cancelar</a>
    </header>
    
    <form action="?section=usuarios-actualizar" method="POST" class="form-crear">
        
        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

        <div class="form-group">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?php echo $usuario['nombre']; ?>" 
                   required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" 
                   value="<?php echo $usuario['email']; ?>" 
                   required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-group">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" class="select-filtro" style="width: 100%;">
                <option value="Empleado" <?php echo ($usuario['rol'] == 'Empleado') ? 'selected' : ''; ?>>Empleado</option>
                <option value="Administrador" <?php echo ($usuario['rol'] == 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </div>

        <div class="form-group">
            <label for="sucursal">Sucursal:</label>
            <select id="sucursal" name="sucursal" class="select-filtro" style="width: 100%;">
                <option value="Centro" <?php echo ($usuario['sucursal'] == 'Centro') ? 'selected' : ''; ?>>Centro</option>
                <option value="Norte" <?php echo ($usuario['sucursal'] == 'Norte') ? 'selected' : ''; ?>>Norte</option>
                <option value="Sur" <?php echo ($usuario['sucursal'] == 'Sur') ? 'selected' : ''; ?>>Sur</option>
            </select>
        </div>

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Actualizar Usuario</button>
        </div>

    </form>
</section>

<style>
    .form-crear { max-width: 500px; background: #fff; padding: 20px; border-radius: 8px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
</style>