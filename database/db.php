<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'portafolio';
$username = 'root'; // Ajustar según tus credenciales
$password = 'umg123'; // Ajustar según tus credenciales

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
