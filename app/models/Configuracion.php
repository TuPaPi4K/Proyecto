<?php
require_once __DIR__ . '/../../config/database.php';

class Configuracion {
    
    public static function get() {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM configuracion WHERE id = 1");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Valores por defecto si la tabla está vacía
        if (!$resultado) {
            return [
                'color_primario' => '#FF6B00',
                'color_secundario' => '#FF8C42',
                'titulo_landing' => 'Pollo NaGuara',
                'descripcion_landing' => 'Sistema de Gestión',
                'telefono' => '',
                'email_contacto' => '',
                'logo_url' => 'assets/logo.png' // Valor por defecto
            ];
        }
        return $resultado;
    }

    public static function update($data) {
        $db = (new Database())->getConnection();
        
        // Agregamos logo_url a la actualización
        $query = "UPDATE configuracion SET 
                    color_primario = :p, 
                    color_secundario = :s,
                    titulo_landing = :t,
                    descripcion_landing = :d,
                    telefono = :tel,
                    email_contacto = :e,
                    logo_url = :l
                  WHERE id = 1";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':p' => $data['primario'],
            ':s' => $data['secundario'],
            ':t' => $data['titulo'],
            ':d' => $data['descripcion'],
            ':tel' => $data['telefono'],
            ':e' => $data['email'],
            ':l' => $data['logo_url'] // Guardamos la ruta de la imagen
        ]);
    }
}