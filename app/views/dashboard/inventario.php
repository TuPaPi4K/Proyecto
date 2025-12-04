<section class="dashboard-section">
    <header class="section-header">
        <h2>Gestión de Inventario</h2>
        <a href="?section=productos-crear" class="btn-primary" style="text-decoration:none;">+ Nuevo Producto</a>
    </header>
    
<section class="stats-grid" style="margin-bottom: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        
        <article class="stat-card">
            <h3> Inventario Total</h3>
            <p class="stat-number"><?php echo number_format($total_stock); ?> <span style="font-size: 1rem; color: #666;">unidades</span></p>
            <p>Total de productos en almacén</p>
        </article>
        
        <article class="stat-card" style="border-left-color: #2E7D32;">
            <h3> Valor Total del Inventario</h3>
            <p class="stat-number" style="color: #2E7D32;">$<?php echo number_format($valor_inventario, 2); ?></p>
            <p>Capital invertido en mercancía</p>
        </article>
    </section>

    <section class="filtros">
        <form action="" method="GET" style="display:flex; gap:1rem; width:100%;">
            
            <input type="hidden" name="section" value="inventario">

            <input type="text" placeholder="Buscar producto..." class="input-busqueda" aria-label="Buscar productos">
            
            <select name="categoria" class="select-filtro" aria-label="Filtrar por categoría" onchange="this.form.submit()">
                <option <?php echo (!isset($filtro_actual) || $filtro_actual == 'Todas las categorías') ? 'selected' : ''; ?>>Todas las categorías</option>
                
                <?php 
                $categorias = ['Pollos Enteros', 'Milanesas', 'Aliños', 'Especias', 'Otros'];
                foreach($categorias as $cat): 
                    $selected = (isset($filtro_actual) && $filtro_actual == $cat) ? 'selected' : '';
                ?>
                    <option value="<?php echo $cat; ?>" <?php echo $selected; ?>><?php echo $cat; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </section>
    
    <table class="tabla-datos">
        <thead>
            <tr>
                <th scope="col">Producto</th>
                <th scope="col">Categoría</th>
                <th scope="col">Stock Actual</th>
                <th scope="col">Stock Mínimo</th>
                <th scope="col">Precio</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_productos as $prod): ?>
                <?php 
                    // Lógica de semáforo actualizada
                    $clase_stock = 'stock-ok'; // Verde por defecto
                    
                    if ($prod['estado'] === 'Sin Stock') {
                        $clase_stock = 'sin-stock'; // Rojo
                    } elseif ($prod['estado'] === 'Stock Bajo') {
                        $clase_stock = 'stock-bajo'; // Naranja
                    }
                ?>
                
                <tr>
                    <td><?php echo $prod['nombre']; ?></td>
                    <td><?php echo $prod['categoria']; ?></td>
                    <td><?php echo $prod['stock_actual']; ?></td>
                    <td><?php echo $prod['stock_minimo']; ?></td>
                    <td>$<?php echo number_format($prod['precio'], 2); ?></td>
                    <td>
                        <mark class="estado <?php echo $clase_stock; ?>">
                            <?php echo $prod['estado']; ?>
                        </mark>
                    </td>
                    <td>
                        <menu class="acciones" style="display:flex; gap: 5px;">
                            <a href="?section=productos-editar&id=<?php echo $prod['id']; ?>" class="btn-editar" style="text-decoration:none;">Editar</a>
                            
                            <a href="?section=productos-eliminar&id=<?php echo $prod['id']; ?>" 
                               class="btn-eliminar" 
                               style="text-decoration:none; border: 1px solid #C62828; background-color: #ffebee; color: #c62828;"
                               onclick="return confirm('¿Eliminar este producto del catálogo?');">
                               Eliminar
                            </a>
                        </menu>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>