<section class="dashboard-section">
    <header class="section-header">
        <h2>Editar Producto: <?php echo $producto['nombre']; ?></h2>
        <a href="?section=inventario" class="btn-eliminar" style="text-decoration:none;">Cancelar</a>
    </header>
    
    <form action="?section=productos-actualizar" method="POST" class="form-crear">
        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select name="categoria" class="select-filtro" style="width: 100%;">
                <?php 
                $cats = ['Pollos Enteros', 'Milanesas', 'Aliños', 'Especias', 'Otros'];
                foreach($cats as $cat) {
                    $selected = ($producto['categoria'] == $cat) ? 'selected' : '';
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label>Stock Actual:</label>
                <input type="number" name="stock_actual" value="<?php echo $producto['stock_actual']; ?>" required class="input-busqueda" style="width: 100%;">
            </div>
            <div>
                <label>Stock Mínimo:</label>
                <input type="number" name="stock_minimo" value="<?php echo $producto['stock_minimo']; ?>" required class="input-busqueda" style="width: 100%;">
            </div>
        </div>

        <div class="form-group">
            <label>Precio ($):</label>
            <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required class="input-busqueda" style="width: 100%;">
        </div>

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Actualizar Producto</button>
        </div>
    </form>
</section>
<style>
    .form-crear { max-width: 600px; background: #fff; padding: 20px; border-radius: 8px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
</style>