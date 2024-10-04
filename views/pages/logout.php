<?php
session_start();
session_unset();  // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión

// Depuración: Verifica si la sesión realmente se eliminó
if (session_status() === PHP_SESSION_NONE) {
    echo "La sesión ha sido destruida correctamente.";
} else {
    echo "Error al destruir la sesión.";
}

header('Location: /freelance/web/index.php');
exit();
?>