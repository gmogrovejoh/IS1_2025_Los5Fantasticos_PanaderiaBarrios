<?php
require_once '../app/core/Controller.php';

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
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar producto']);
            }
        }
    }

    public function actualizar() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_producto = (int)$_POST['id_producto'];
            $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : null;
            $monto_solicitado = isset($_POST['monto_solicitado']) ? (int)$_POST['monto_solicitado'] : null;

            $resultado = $this->carritoModel->agregarProducto($_SESSION['usuario_id'], $id_producto, $cantidad, $monto_solicitado);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Carrito actualizado']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar carrito']);
            }
        }
    }
}
?>