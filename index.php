<?php
session_start();
require_once 'config/database.php';
require_once 'routes.php';

// Autoload de clases
spl_autoload_register(function ($class) {
    $directories = ['models/', 'controllers/'];
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Obtener la acción de la URL
$action = $_GET['action'] ?? 'home';
$controller = $_GET['controller'] ?? 'home';

// Enrutar la solicitud
route($controller, $action);
?>