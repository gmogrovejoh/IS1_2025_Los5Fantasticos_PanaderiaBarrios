<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../conexion.php";
require_once "../core/RepositorioGenerico.php";

$repo = new RepositorioGenerico($conexion, "producto", "id_producto");

$input = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case "GET":
        if (isset($_GET["id"])) {
            $data = $repo->obtenerPorId(intval($_GET["id"]));
            echo json_encode($data ?: ["error" => "Producto no encontrado"]);
        } else {
            echo json_encode($repo->obtenerTodos());
        }
        break;

    case "POST":
        if (!$input) {
            echo json_encode(["error" => "JSON inválido"]);
            exit;
        }

        $id = $repo->crear([
            "nombre" => $input["nombre"],
            "precio_b2c" => $input["precio_b2c"],
            "id_categoria" => 1
        ]);

        echo json_encode(["mensaje" => "Producto creado", "id" => $id]);
        break;

    case "PUT":
        if (!$input || !isset($input["id_producto"])) {
            echo json_encode(["error" => "ID requerido"]);
            exit;
        }

        $ok = $repo->actualizar(
            intval($input["id_producto"]),
            [
                "nombre" => $input["nombre"],
                "precio_b2c" => $input["precio_b2c"]
            ]
        );

        echo json_encode(["mensaje" => $ok ? "Producto actualizado" : "Error al actualizar"]);
        break;

    case "DELETE":
        if (!$input || !isset($input["id"])) {
            echo json_encode(["error" => "ID requerido"]);
            exit;
        }

        $ok = $repo->eliminar(intval($input["id"]));
        echo json_encode(["mensaje" => $ok ? "Producto eliminado" : "Error al eliminar"]);
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
}