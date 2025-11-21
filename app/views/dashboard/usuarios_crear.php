<section class="dashboard-section">
    <header class="section-header">
        <h2>Nuevo Usuario</h2>
        <a href="?section=usuarios" class="btn-eliminar">Cancelar</a>
    </header>
    
    <form action="?section=usuarios-guardar" method="POST" class="form-crear">
        
        <div class="form-group">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-group">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" class="select-filtro" style="width: 100%;">
                <option value="Vendedor">Vendedor</option>
                <option value="Administrador">Administrador</option>
                <option value="Inventario">Inventario</option>
            </select>
        </div>

        <div class="form-group">
            <label for="sucursal">Sucursal:</label>
            <select id="sucursal" name="sucursal" class="select-filtro" style="width: 100%;">
                <option value="Centro">Centro</option>
                <option value="Norte">Norte</option>
                <option value="Sur">Sur</option>
            </select>
        </div>

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Guardar Usuario</button>
        </div>

    </form>
</section>

<style>
    .form-crear { max-width: 500px; background: #fff; padding: 20px; border-radius: 8px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
</style>