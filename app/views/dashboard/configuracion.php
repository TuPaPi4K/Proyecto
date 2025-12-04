<section class="dashboard-section">
    <header class="section-header">
        <h2>Configuración del Sistema</h2>
    </header>

    <form action="?section=config-guardar" method="POST" enctype="multipart/form-data" class="form-crear" style="max-width: 800px;">
        
        <input type="hidden" name="logo_actual" value="<?php echo $config['logo_url']; ?>">

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            
            <div>
                <h3 style="margin-bottom:1rem; color:var(--naranja-primario);">🎨 Apariencia</h3>
                
                <div class="form-group">
                    <label>Logo del Sistema:</label>
                    <div style="margin-bottom:10px; background:#eee; padding:10px; text-align:center; border-radius:8px;">
                        <img src="<?php echo $config['logo_url']; ?>" alt="Logo Actual" style="max-height: 80px;">
                    </div>
                    <input type="file" name="logo_imagen" accept="image/*" class="input-busqueda" style="width:100%;">
                    <small style="color:#666;">Sube una imagen (PNG o JPG) para cambiar el logo.</small>
                </div>

                <div class="form-group">
                    <label>Color Principal:</label>
                    <input type="color" name="color_primario" value="<?php echo $config['color_primario']; ?>" style="width:100%; height:40px; cursor:pointer;">
                </div>
                <div class="form-group">
                    <label>Color Secundario:</label>
                    <input type="color" name="color_secundario" value="<?php echo $config['color_secundario']; ?>" style="width:100%; height:40px; cursor:pointer;">
                </div>
            </div>

            <div>
                <h3 style="margin-bottom:1rem; color:var(--naranja-primario);">ℹ️ Información Pública</h3>
                <div class="form-group">
                    <label>Nombre de la Empresa:</label>
                    <input type="text" name="titulo_landing" value="<?php echo $config['titulo_landing']; ?>" class="input-busqueda" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" value="<?php echo $config['telefono']; ?>" class="input-busqueda" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email_contacto" value="<?php echo $config['email_contacto']; ?>" class="input-busqueda" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Descripción (Landing):</label>
                    <textarea name="descripcion_landing" class="input-busqueda" style="width:100%; height:80px;"><?php echo $config['descripcion_landing']; ?></textarea>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary" style="flex: 1; padding: 1rem; font-size: 1.1rem;">
                💾 Guardar Configuración
            </button>
            
            <a href="?section=config-reset" 
               class="btn-eliminar" 
               style="text-decoration: none; padding: 1rem; font-size: 1.1rem; text-align: center; border: 1px solid #C62828; background: #FFEBEE; color: #C62828; border-radius: 4px;"
               onclick="return confirm('¿Seguro que quieres borrar todos los cambios y volver a los colores originales?');">
                🔄 Restablecer Predeterminado
            </a>
        </div>
    </form>
</section>