<?php
class CarritoController {
    
    public function index() {
        if (!isset($_SESSION['cliente'])) {
            header('Location: index.php?controller=cliente&action=login');
            exit;
        }
        
        $carritoModel = new Carrito();
        $id_carrito = $carritoModel->obtenerOCrearCarrito($_SESSION['cliente']['id_cliente']);
        $productos = $carritoModel->obtenerProductos($id_carrito);
        $total = $carritoModel->obtenerTotal($id_carrito);
        
        include 'views/carrito/index.php';
    }
    
    public function agregar() {
        if (!isset($_SESSION['cliente'])) {
            echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
            exit;
        }
        
        $id_producto = $_POST['id_producto'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 1;
        
        if (!$id_producto) {
            echo json_encode(['success' => false, 'message' => 'Producto no válido']);
            exit;
        }
        
        $carritoModel = new Carrito();
        $id_carrito = $carritoModel->obtenerOCrearCarrito($_SESSION['cliente']['id_cliente']);
        
        if ($carritoModel->agregarProducto($id_carrito, $id_producto, $cantidad)) {
            echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar producto']);
        }
        exit;
    }
    
    public function actualizar() {
        if (!isset($_SESSION['cliente'])) {
            echo json_encode(['success' => false]);
            exit;
        }
        
        $id_producto = $_POST['id_producto'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 0;
        
        $carritoModel = new Carrito();
        $id_carrito = $carritoModel->obtenerOCrearCarrito($_SESSION['cliente']['id_cliente']);
        
        $success = $carritoModel->actualizarCantidad($id_carrito, $id_producto, $cantidad);
        $total = $carritoModel->obtenerTotal($id_carrito);
        
        echo json_encode(['success' => $success, 'total' => $total]);
        exit;
    }
    
    public function eliminar() {
        if (!isset($_SESSION['cliente'])) {
            echo json_encode(['success' => false]);
            exit;
        }
        
        $id_producto = $_POST['id_producto'] ?? null;
        
        $carritoModel = new Carrito();
        $id_carrito = $carritoModel->obtenerOCrearCarrito($_SESSION['cliente']['id_cliente']);
        
        $success = $carritoModel->eliminarProducto($id_carrito, $id_producto);
        
        echo json_encode(['success' => $success]);
        exit;
    }
}
?>