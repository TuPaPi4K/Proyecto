<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    
    public static function all($filtroRol = null) {
        $db = (new Database())->getConnection();
        // Quitamos 'sucursal' del SELECT
        $sql = "SELECT id, nombre, email, rol, IF(estado=1, 'Activo', 'Inactivo') as estado FROM usuarios";

        if ($filtroRol && $filtroRol != 'Todos los roles') {
            $sql .= " WHERE rol = :rol";
        }
        $stmt = $db->prepare($sql);
        if ($filtroRol && $filtroRol != 'Todos los roles') {
            $stmt->bindParam(':rol', $filtroRol);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = (new Database())->getConnection();
        // Quitamos 'sucursal' del INSERT
        $query = "INSERT INTO usuarios (nombre, email, password, rol, estado) 
                  VALUES (:nombre, :email, :password, :rol, 1)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':rol', $data['rol']);
        return $stmt->execute();
    }

    public static function find($id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($data) {
        $db = (new Database())->getConnection();
        // Quitamos 'sucursal' del UPDATE
        $query = "UPDATE usuarios SET nombre = :nombre, email = :email, rol = :rol WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':rol', $data['rol']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    public static function delete($id) {
        $db = (new Database())->getConnection();
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public static function cambiarEstado($id, $nuevo_estado) {
        $db = (new Database())->getConnection();
        $query = "UPDATE usuarios SET estado = :estado WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':estado', $nuevo_estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public static function findByEmail($email) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email AND estado = 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function resetPassword($id, $password_hash) {
        $db = (new Database())->getConnection();
        $query = "UPDATE usuarios SET password = :password WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}