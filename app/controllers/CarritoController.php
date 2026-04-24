<?php

class CarritoController extends Controller {
    private $carritoModel;

    public function __construct() {
        $this->carritoModel = $this->model('Carrito');
    }

    public function agregar() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_producto = (int)$_POST['id_producto'];
            $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : null;
            $monto_solicitado = isset($_POST['monto_solicitado']) ? (int)$_POST['monto_solicitado'] : null;

            $resultado = $this->carritoModel->agregarProducto($_SESSION['usuario_id'], $id_producto, $cantidad, $monto_solicitado);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar producto']);
            }
        }
    }

    public function eliminar() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_producto = (int)$_POST['id_producto'];
            $resultado = $this->carritoModel->eliminarProducto($_SESSION['usuario_id'], $id_producto);
            echo json_encode(['success' => $resultado]);
        }
    }

    public function actualizar() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_producto = (int)$_POST['id_producto'];
            
            // Determinar si es actualización de cantidad o monto
            $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : null;
            $monto = isset($_POST['monto_solicitado']) ? (float)$_POST['monto_solicitado'] : null;

            // Llamamos a una función específica de actualización para no duplicar lógica
            // Nota: Usamos agregarProducto porque tu modelo usa INSERT ... ON DUPLICATE KEY UPDATE lógica
            // Pero para ser más limpios, asegúrate que el modelo Carrito::agregarProducto maneje actualizaciones.
            $resultado = $this->carritoModel->actualizarProductoCarrito($_SESSION['usuario_id'], $id_producto, $cantidad, $monto);
            
            echo json_encode(['success' => $resultado]);
        }
    }
}
?>