<section class="dashboard-section" id="factura">
    <header class="section-header">
        <h2>Detalle de Venta: <?php echo $venta['codigo_venta']; ?></h2>
        <div class="no-print">
            <button onclick="window.print()" class="btn-primary">🖨️ Imprimir</button>
            <a href="?section=ventas" class="btn-eliminar" style="text-decoration:none;">Volver</a>
        </div>
    </header>

    <div class="factura-box" style="background: white; padding: 2rem; border: 1px solid #eee;">
        <div style="display:flex; justify-content:space-between; margin-bottom: 2rem; border-bottom: 2px solid #FF6B00; padding-bottom: 1rem;">
            <div>
                <h1 style="color: #FF6B00; margin:0;">Pollo Na'Guara</h1>
                <p>La mejor calidad en pollos y aliños</p>
            </div>
            <div style="text-align:right;">
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?></p>
                <p><strong>Vendedor:</strong> <?php echo $venta['vendedor']; ?></p>
                <p><strong>Estado:</strong> <?php echo $venta['estado']; ?></p>
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <p><strong>Cliente:</strong> <?php echo $venta['cliente']; ?></p>
        </div>

        <table class="tabla-datos" style="width:100%;">
            <thead>
                <tr>
                    <th style="background:#eee; color:#333;">Producto</th>
                    <th style="background:#eee; color:#333; text-align:center;">Cant.</th>
                    <th style="background:#eee; color:#333; text-align:right;">Precio Unit.</th>
                    <th style="background:#eee; color:#333; text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $item): ?>
                <tr>
                    <td><?php echo $item['producto_nombre']; ?></td>
                    <td style="text-align:center;"><?php echo $item['cantidad']; ?></td>
                    <td style="text-align:right;">$<?php echo number_format($item['precio_unitario'], 2); ?></td>
                    <td style="text-align:right;">$<?php echo number_format($item['cantidad'] * $item['precio_unitario'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:bold; font-size:1.2rem; padding-top:1rem;">TOTAL:</td>
                    <td style="text-align:right; font-weight:bold; font-size:1.2rem; color:#FF6B00; padding-top:1rem;">
                        $<?php echo number_format($venta['total'], 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

<style>
    @media print {
        .dashboard-nav, .dashboard-header, .no-print, header.section-header {
            display: none !important;
        }
        .dashboard-main {
            margin: 0; padding: 0;
        }
        .dashboard-section {
            box-shadow: none;
            border: none;
        }
        body {
            background: white;
        }
    }
</style>