<section class="dashboard-section">
    <header class="section-header">
        <h2>Gestión de Inventario</h2>
        <a href="?section=productos-crear" class="btn-primary" style="text-decoration:none;">+ Nuevo Producto</a>
    </header>
    
    <section class="filtros">
        <input type="text" placeholder="Buscar producto..." class="input-busqueda" aria-label="Buscar productos">
        <select class="select-filtro" aria-label="Filtrar por categoría">
            <option>Todas las categorías</option>
            <option>Pollos Enteros</option>
            <option>Milanesas</option>
            <option>Aliños</option>
            <option>Especias</option>
        </select>
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
                    // Lógica de presentación: Determinar clase CSS según el estado
                    // Si dice 'Stock Bajo', usamos la clase 'stock-bajo', si no 'stock-ok'
                    $clase_stock = ($prod['estado'] === 'Stock Bajo') ? 'stock-bajo' : 'stock-ok';
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