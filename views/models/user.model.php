<?php
class UserModel {
    public static function getUserByEmail($email) {
        include '../../database/db.php'; // Ruta corregida

        // Verifica si la variable $pdo está definida
        if (!isset($pdo)) {
            throw new Exception('No se pudo establecer la conexión a la base de datos');
        }

        // Prepara la consulta SQL para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(); // Devuelve los datos del usuario
    }
}
?>
