<?php
class PedidoController {
    
    public function checkout() {
        if (!isset($_SESSION['cliente'])) {
            header('Location: index.php?controller=cliente&action=login');
            exit;
        }
        
        $carritoModel = new Carrito();
        $id_carrito = $carritoModel->obtenerOCrearCarrito($_SESSION['cliente']['id_cliente']);
        $productos = $carritoModel->obtenerProductos($id_carrito);
        $total = $carritoModel->obtenerTotal($id_carrito);
        
        if (empty($productos)) {
            header('Location: index.php?controller=carrito&action=index');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos_pedido = [
                'tipo_retiro' => $_POST['tipo_retiro'] ?? 'tienda',
                'costo_envio' => $_POST['tipo_retiro'] === 'domicilio' ? 5.00 : 0.00,
                'costo_producto' => $total,
                'costo_total' => $total + ($_POST['tipo_retiro'] === 'domicilio' ? 5.00 : 0.00),
                'fecha_entrega' => $_POST['fecha_entrega'] ?? date('Y-m-d', strtotime('+1 day')),
                'hora_entrega' => $_POST['hora_entrega'] ?? '10:00:00',
                'id_cliente' => $_SESSION['cliente']['id_cliente'],
                'id_sede' => 1, // Sede por defecto
                'id_ubicacion' => null
            ];
            
            $pedidoModel = new Pedido();
            $id_pedido = $pedidoModel->crear($datos_pedido);
            
            if ($id_pedido) {
                $pedidoModel->agregarProductos($id_pedido, $productos);
                $carritoModel->vaciarCarrito($id_carrito);
                
                header('Location: index.php?controller=pedido&action=confirmacion&id=' . $id_pedido);
                exit;
            } else {
                $error = "Error al procesar el pedido";
            }
        }
        
        include 'views/pedido/checkout.php';
    }
    
    public function confirmacion() {
        $id_pedido = $_GET['id'] ?? null;
        
        if (!$id_pedido || !isset($_SESSION['cliente'])) {
            header('Location: index.php');
            exit;
        }
        
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id_pedido);
        
        if (!$pedido || $pedido['id_cliente'] != $_SESSION['cliente']['id_cliente']) {
            header('Location: index.php');
            exit;
        }
        
        $productos = $pedidoModel->obtenerProductosPedido($id_pedido);
        
        include 'views/pedido/confirmacion.php';
    }
    
    public function mis_pedidos() {
        if (!isset($_SESSION['cliente'])) {
            header('Location: index.php?controller=cliente&action=login');
            exit;
        }
        
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPorCliente($_SESSION['cliente']['id_cliente']);
        
        include 'views/pedido/mis_pedidos.php';
    }
}
?>