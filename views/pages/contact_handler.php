<?php
session_start();
include '../../database/db.php'; // Cambia la ruta según tu estructura

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['name'];
    $email_address = $_POST['email'];
    $phone_number = $_POST['phone'];
    $message = $_POST['message'];

    // Validar campos obligatorios
    if (!empty($full_name) && !empty($email_address) && !empty($message)) {
        // Insertar los datos en la base de datos
        $stmt = $pdo->prepare("INSERT INTO contact_me (full_name, email_address, phone_number, message) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$full_name, $email_address, $phone_number, $message])) {
            $response['status'] = 'success';
            $response['message'] = 'Mensaje enviado correctamente.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al guardar el mensaje.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Por favor, completa todos los campos requeridos.';
    }

    echo json_encode($response);
    exit();
}
?>
