<section class="dashboard-section">
    <header class="section-header">
        <h2>Mi Perfil</h2>
    </header>
    
    <div style="max-width: 500px; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 1.5rem; color: var(--naranja-primario);">Cambiar mi Contraseña</h3>
        
        <form action="?section=perfil-guardar" method="POST">
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:bold;">Nueva Contraseña:</label>
                <input type="password" name="password" required class="input-busqueda" style="width: 100%; padding: 0.8rem;" placeholder="Escribe tu nueva clave">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:bold;">Confirmar Contraseña:</label>
                <input type="password" name="confirm_password" required class="input-busqueda" style="width: 100%; padding: 0.8rem;" placeholder="Repite la clave">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem;">💾 Guardar Nueva Clave</button>
        </form>
    </div>
</section>