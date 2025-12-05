<?php
require_once "config.php";
require_once "Database.php";

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "✔ Conexión exitosa a la base de datos: " . DB_NAME;
}
?>