<?php
require_once '../app/core/Controller.php';

class PedidoController extends Controller {
    private $pedidoModel;
    private $carritoModel;

    public function __construct() {
        $this->pedidoModel = $this->model('Pedido');
        $this->carritoModel = $this->model('Carrito');
    }

    public function procesar() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productos_carrito = $this->carritoModel->obtenerProductosCarrito($_SESSION['usuario_id']);
            
            if (empty($productos_carrito)) {
                $this->redirect('cliente/carrito');
                return;
            }

            $subtotal = $this->carritoModel->calcularSubtotal($_SESSION['usuario_id'], $_SESSION['usuario_rol']);
            $costo_envio = 0;
            
            // Calcular costo de envío para B2B
            if ($_SESSION['usuario_rol'] != 'CLIENTE_ESTANDAR' && $_POST['tipo_entrega'] == 'DOMICILIO') {
                // Aquí iría la lógica para calcular el costo de envío
                $costo_envio = 10.00; // Valor por defecto
            }

            $datos_pedido = [
                'id_cliente' => $_SESSION['usuario_id'],
                'id_sede' => 1, // Sede principal
                'id_direccion_entrega' => $_POST['tipo_entrega'] == 'DOMICILIO' ? $_POST['id_direccion'] : null,
                'tipo_entrega' => $_POST['tipo_entrega'],
                'fecha_entrega' => $_POST['fecha_entrega'],
                'ventana_entrega' => $_POST['ventana_entrega'],
                'subtotal_productos' => $subtotal,
                'costo_envio' => $costo_envio,
                'costo_total' => $subtotal + $costo_envio,
                'tipo_comprobante' => ($_SESSION['usuario_rol'] == 'EMPRESA_FACTURA') ? 'FACTURA' : 'BOLETA',
                'rol_cliente' => $_SESSION['usuario_rol']
            ];

            $id_pedido = $this->pedidoModel->crear($datos_pedido, $productos_carrito);
            
            if ($id_pedido) {
                // Vaciar carrito
                $this->carritoModel->vaciarCarrito($_SESSION['usuario_id']);
                
                $data['success'] = 'Pedido creado exitosamente. ID: ' . $id_pedido;
                $data['id_pedido'] = $id_pedido;
                $this->view('cliente_b2c/pedido_confirmado', $data);
            } else {
                $data['error'] = 'Error al procesar el pedido';
                $this->redirect('cliente/checkout');
            }
        }
    }

    public function hojaProduccion() {
        // Esta función será llamada desde AdminController
        $fecha_entrega = $_GET['fecha'] ?? date('Y-m-d');
        $ventana_entrega = $_GET['ventana'] ?? 'MAÑANA';
        
        $produccion = $this->pedidoModel->calcularProduccionTotal($fecha_entrega, $ventana_entrega);
        
        $data['fecha_entrega'] = $fecha_entrega;
        $data['ventana_entrega'] = $ventana_entrega;
        $data['produccion'] = $produccion;
        
        return $data;
    }
}
?>