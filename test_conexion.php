<?php
$db_name = 'PanaderiaBarriosDB'; 
$db_user = 'root'; 
$db_pass = 'fanwhy1'; 

try {
    $pdo = new PDO("mysql:host=localhost;dbname={$db_name}", $db_user, $db_pass);
    echo "¡Conexión Exitosa! La BD está activa y accesible.";
} catch (PDOException $e) {
    echo "Fallo de Conexión. Revisa MySQL y credenciales. Error: " . $e->getMessage();
}
?>