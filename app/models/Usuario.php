<?php
// Importamos la conexión
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    
    public static function all($filtroRol = null) {
        $db = (new Database())->getConnection();
        
        $sql = "SELECT 
                    id, 
                    nombre, 
                    email, 
                    rol, 
                    sucursal, 
                    IF(estado=1, 'Activo', 'Inactivo') as estado 
                  FROM usuarios";

        // Si hay filtro, agregamos el WHERE
        if ($filtroRol && $filtroRol != 'Todos los roles') {
            $sql .= " WHERE rol = :rol";
        }

        $stmt = $db->prepare($sql);

        // Si hay filtro, vinculamos el parámetro
        if ($filtroRol && $filtroRol != 'Todos los roles') {
            $stmt->bindParam(':rol', $filtroRol);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = (new Database())->getConnection();

        // Preparamos la consulta SQL de inserción
        $query = "INSERT INTO usuarios (nombre, email, rol, sucursal, estado) 
                  VALUES (:nombre, :email, :rol, :sucursal, 1)"; // 1 = Activo por defecto
        
        $stmt = $db->prepare($query);
        
        // Vinculamos los datos reales (evita inyección SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':rol', $data['rol']);
        $stmt->bindParam(':sucursal', $data['sucursal']);
        
        // ¡Fuego!
        return $stmt->execute();
    }

    // 1. Buscar un solo usuario por ID (Para llenar el formulario)
    public static function find($id) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Actualizar los datos en la BD
    public static function update($data) {
        $db = (new Database())->getConnection();
        
        $query = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    email = :email, 
                    rol = :rol, 
                    sucursal = :sucursal 
                  WHERE id = :id"; // <--- ¡La cláusula WHERE es vital para no borrar a todos!
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':rol', $data['rol']);
        $stmt->bindParam(':sucursal', $data['sucursal']);
        $stmt->bindParam(':id', $data['id']); // Necesitamos el ID para saber a quién actualizar
        
        return $stmt->execute();
    }

    // 3. Eliminar usuario físicamente de la BD
    public static function delete($id) {
        $db = (new Database())->getConnection();
        
        // Sentencia letal
        $query = "DELETE FROM usuarios WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // 4. Cambiar estado (Activar/Desactivar)
    public static function cambiarEstado($id, $nuevo_estado) {
        $db = (new Database())->getConnection();
        
        $query = "UPDATE usuarios SET estado = :estado WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':estado', $nuevo_estado);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Buscar usuario por email (Para el login)
    public static function findByEmail($email) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email AND estado = 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}