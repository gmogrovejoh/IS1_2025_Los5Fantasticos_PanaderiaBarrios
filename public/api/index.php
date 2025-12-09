<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../../app/core/Config.php';
require_once __DIR__ . '/../../app/core/Database.php';

require_once __DIR__ . '/../../app/models/Producto.php';

require_once __DIR__ . '/../../app/api/controllers/ProductoApiController.php';

$uri = $_SERVER['REQUEST_URI'];
$uri = preg_replace("#^.*/public/api/#", "", $_SERVER['REQUEST_URI']);
$uri = trim($uri, "/");
$segments = explode("/", $uri);

$endpoint = $segments[0] ?? "";
$id = $segments[1] ?? null;

$controller = new ProductoApiController();

switch ($endpoint) {
    
    case "productos":
        if ($id !== null) {
            $controller->obtenerPorId($id);
        } else {
            $controller->obtenerTodos();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
}
?>