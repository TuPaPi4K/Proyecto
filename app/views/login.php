<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pollo Na'Guara</title>
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background: linear-gradient(135deg, #FF6B00, #FF8C42); margin: 0; font-family: sans-serif; }
        .login-card { background: white; padding: 2rem; border-radius: 8px; width: 100%; max-width: 350px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .input-login { width: 90%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-login { width: 96%; padding: 10px; background: #FF6B00; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-login:hover { background: #e65100; }
        .error { color: red; margin-bottom: 10px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 style="color: #333;">Pollo Na'Guara</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="?section=login-auth" method="POST">
            <input type="email" name="email" placeholder="Email" class="input-login" required>
            <input type="password" name="password" placeholder="Contraseña" class="input-login" required>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</body>
</html>