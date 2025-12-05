<?php
function route($controller, $action) {
    $controllerName = ucfirst($controller) . 'Controller';
    
    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();
        
        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
        } else {
            // Acción no encontrada, mostrar error 404
            header("HTTP/1.0 404 Not Found");
            include 'views/errors/404.php';
        }
    } else {
        // Controlador no encontrado, mostrar error 404
        header("HTTP/1.0 404 Not Found");
        include 'views/errors/404.php';
    }
}
?>