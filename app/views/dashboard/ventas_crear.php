<section class="dashboard-section">
    <header class="section-header">
        <h2>Nueva Venta</h2>
        <a href="?section=ventas" class="btn-eliminar" style="text-decoration:none;">Cancelar</a>
    </header>
    
    <form action="?section=ventas-guardar" method="POST" class="form-crear">
        
        <div class="form-group">
            <label for="cliente">Cliente / Razón Social:</label>
            <input type="text" name="cliente" required class="input-busqueda" placeholder="Ej: Consumidor Final" style="width: 100%;">
        </div>

        <div class="form-group">
            <label for="producto_id">Producto a vender:</label>
            <select name="producto_id" id="producto_id" class="select-filtro" style="width: 100%;" required onchange="actualizarTotal()">
                <option value="" data-precio="0">Seleccione un producto...</option>
                <?php foreach($lista_productos as $prod): ?>
                    <option value="<?php echo $prod['id']; ?>" 
                            data-precio="<?php echo $prod['precio']; ?>"
                            data-stock="<?php echo $prod['stock_actual']; ?>">
                        <?php echo $prod['nombre']; ?> (Stock: <?php echo $prod['stock_actual']; ?>) - $<?php echo $prod['precio']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" value="1" min="1" required class="input-busqueda" style="width: 100%;" onchange="actualizarTotal()" onkeyup="actualizarTotal()">
            </div>
            <div>
                <label>Total Estimado ($):</label>
                <input type="text" id="total_preview" readonly class="input-busqueda" style="width: 100%; background-color: #f0f0f0; font-weight: bold; color: #FF6B00;">
            </div>
        </div>

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">✅ Cobrar y Facturar</button>
        </div>
    </form>
</section>

<script>
function actualizarTotal() {
    const select = document.getElementById('producto_id');
    const cantidadInput = document.getElementById('cantidad');
    const totalInput = document.getElementById('total_preview');
    
    // Obtenemos el precio del atributo data-precio de la opción seleccionada
    const precio = parseFloat(select.options[select.selectedIndex].dataset.precio) || 0;
    const cantidad = parseFloat(cantidadInput.value) || 0;
    
    const total = precio * cantidad;
    totalInput.value = total.toFixed(2);
}
</script>

<style>
    .form-crear { max-width: 600px; background: #fff; padding: 20px; border-radius: 8px; margin: 0 auto; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
</style>