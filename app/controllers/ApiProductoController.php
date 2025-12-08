<?php
require_once '../app/core/Controller.php';
require_once '../vendor/autoload.php';

use OpenApi\Annotations as OA;

class ApiProductoController extends Controller {
    private $productoModel;

    public function __construct() {
        $this->productoModel = $this->model('Producto');
    }

    /**
     * @OA\Get(
     *     path="/ApiProducto/index",
     *     summary="Listar productos",
     *     tags={"Productos"},
     *     @OA\Response(response="200", description="OK")
     * )
     */
    public function index() {
        // ... tu código ...
        header('Content-Type: application/json');
        echo json_encode(['status' => 200, 'data' => []]);
    }
    
    // ... el resto de tus métodos ...
}
?>