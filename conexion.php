<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pan";

// Conexión correcta sin argumentos nombrados
$conexion = new mysqli($host, $user, $pass, $db);

// Validación
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// OPCIONAL — SOLO si necesitas sesión en TODA la web
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
?>
