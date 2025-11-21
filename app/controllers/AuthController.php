<?php
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    
    public function login() {
        require_once __DIR__ . '/../Views/login.php';
    }

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

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ?section=login");
        exit();
    }
}