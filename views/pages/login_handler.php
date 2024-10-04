<?php
session_start();
include '../models/user.model.php'; // Incluye el archivo del modelo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Llama al modelo para obtener al usuario por su email
    $user = UserModel::getUserByEmail($email);

    // Verifica si el usuario existe
    if ($user) {
        // Imprime el hash de la base de datos y la contraseña ingresada para verificar
        echo "Password Hash in DB: " . $user['password'] . "<br>";
        echo "Password entered: " . $password . "<br>";

        // Verifica si la contraseña ingresada coincide con el hash almacenado
        if (password_verify($password, $user['password'])) {
            echo "Password is correct!<br>";

            // Guardar la sesión del usuario
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirigir al dashboard
            header('Location: /freelance/web/views/pages/dashboard.php');
            exit();
        } else {
            // La contraseña no coincide
            echo "Password is incorrect!<br>";
            echo "Invalid email or password";
        }
    } else {
        // Si no se encuentra el usuario
        echo "User not found!";
    }
} else {
    header('Location: /freelance/web/views/pages/login.php');
    exit();
}
?>
