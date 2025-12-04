<?php
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    
    // 1. Mostrar formulario de Login
    public function login() {
        require_once __DIR__ . '/../Views/login.php';
    }

    // 2. Procesar el Login
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Buscamos al usuario por email
            $usuario = Usuario::findByEmail($email);

            // Verificamos contraseña
            if ($usuario && password_verify($password, $usuario['password'])) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                
                header("Location: ?section=stats");
                exit();
            } else {
                $error = "Credenciales incorrectas";
                require_once __DIR__ . '/../Views/login.php';
            }
        }
    }

    // 3. Cerrar Sesión
    public function logout() {
        session_start();
        session_destroy();
        header("Location: ?section=login");
        exit();
    }

    // 4. Mostrar formulario de Registro (¡ESTA ES LA QUE TE FALTABA!)
    public function registro() {
        require_once __DIR__ . '/../Views/registro.php';
    }

    // 5. Procesar el Registro (Con la corrección de variables y el modal)
    public function registrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recogemos datos de forma segura
            $nombre = $_POST['nombre'] ?? null;
            $email = $_POST['email'] ?? null;
            $pass = $_POST['password'] ?? null;

            if (!$nombre || !$email || !$pass) {
                echo "<script>alert('Error: Faltan datos.'); window.history.back();</script>";
                exit();
            }
            
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            
            $datos = [
                'nombre' => $nombre,
                'email' => $email,
                'password' => $hash,
                'rol' => 'Empleado',    // Rol automático
            ];
            
            try {
                Usuario::create($datos);
                // Redirigimos con la señal para mostrar el Modal de éxito
                header("Location: ?section=login&registro=1");
                exit();
            } catch (Exception $e) {
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
                exit();
            }
        }
    }
}