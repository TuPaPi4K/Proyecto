<section class="dashboard-section">
    <header class="section-header">
        <h2>Nuevo Producto</h2>
        <a href="?section=inventario" class="btn-eliminar" style="text-decoration:none;">Cancelar</a>
    </header>
    
    <form action="?section=productos-guardar" method="POST" class="form-crear">
        
        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" class="select-filtro" style="width: 100%;">
                <option value="Pollos Enteros">Pollos Enteros</option>
                <option value="Milanesas">Milanesas</option>
                <option value="Aliños">Aliños</option>
                <option value="Especias">Especias</option>
                <option value="Otros">Otros</option>
            </select>
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label for="stock_actual">Stock Inicial:</label>
                <input type="number" id="stock_actual" name="stock_actual" required class="input-busqueda" style="width: 100%;">
            </div>
            <div>
                <label for="stock_minimo">Stock Mínimo (Alerta):</label>
                <input type="number" id="stock_minimo" name="stock_minimo" required class="input-busqueda" style="width: 100%;">
            </div>
        </div>

        <div class="form-group">
            <label for="precio">Precio ($):</label>
            <input type="number" step="0.01" id="precio" name="precio" required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Guardar Producto</button>
        </div>

    </form>
</section>

<style>
    .form-crear { max-width: 600px; background: #fff; padding: 20px; border-radius: 8px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
</style>