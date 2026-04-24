<?php

class PedidoController extends Controller {
    private $pedidoModel;
    private $carritoModel;

    public function __construct() {
        $this->pedidoModel = $this->model('Pedido');
        $this->carritoModel = $this->model('Carrito');
    }

    // SOLUCIÓN AL ERROR FATAL: Redirigir index a historial
    public function index() {
        $this->historial();
    }

    // Función para ver la lista de pedidos
    public function historial() {
        $this->requireAuth();

        if ($_SESSION['usuario_rol'] == 'ADMIN') {
            $this->redirect('admin/');
            return;
        }

        $pedidos = $this->pedidoModel->obtenerPorCliente($_SESSION['usuario_id']);
        $data['pedidos'] = $pedidos;
        $this->view('cliente_b2b/historial', $data);
    }

    public function procesar() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // --- NUEVA VALIDACIÓN DE FECHA ---
            $fecha_solicitada = $_POST['fecha_entrega'];
            $fecha_minima = date('Y-m-d', strtotime('+1 day')); // Mañana

            // Si la fecha solicitada es menor a mañana (es decir, hoy o ayer)
            if ($fecha_solicitada < $fecha_minima) {
                // Obtenemos productos y subtotal para recargar la vista con el error
                $productos_carrito = $this->carritoModel->obtenerProductos($_SESSION['usuario_id']);
                $subtotal = $this->carritoModel->calcularTotal($_SESSION['usuario_id']);
                
                // Cargamos datos para que no se rompa la vista
                $data = [
                    'productos' => $productos_carrito,
                    'subtotal' => $subtotal,
                    'error' => 'Error: Los pedidos deben realizarse con al menos 1 día de anticipación (A partir de mañana).',
                    // Si tienes el método de direcciones, cárgalo también aquí
                    'direcciones' => $this->model('Cliente')->obtenerDirecciones($_SESSION['usuario_id'])
                ];
                
                $this->view('cliente_b2b/checkout', $data);
                return; // DETENEMOS LA EJECUCIÓN
            }
            // ---------------------------------

            $productos_carrito = $this->carritoModel->obtenerProductos($_SESSION['usuario_id']);
            if (empty($productos_carrito)) {
                $this->redirect('cliente/carrito');
                return;
            }

            $subtotal = $this->carritoModel->calcularTotal($_SESSION['usuario_id']);
            $costo_envio = 0;

            if ($_POST['tipo_entrega'] === 'DOMICILIO' && !empty($_POST['id_direccion'])) {
                // Usamos la misma función del modelo para seguridad
                $costo_envio = $this->pedidoModel->calcularCostoEnvio($_POST['id_direccion'], $subtotal);
            }

            // Validación estricta de entrega
            if ($_POST['tipo_entrega'] === 'DOMICILIO') {
                $costo_envio = 10.00; // O lógica de zona
                if (!isset($_POST['id_direccion']) || empty($_POST['id_direccion'])) {
                    // Si seleccionó domicilio pero no dirección, forzamos recojo o error
                    // Para simplificar, asumimos que el HTML required funciona, pero si falla:
                    $id_direccion = null; 
                    // Lo ideal sería: mostrar error y volver.
                } else {
                    $id_direccion = $_POST['id_direccion'];
                }
            }

            $datos_pedido = [
                'id_cliente' => $_SESSION['usuario_id'],
                'id_sede' => 1,
                'id_direccion_entrega' => $id_direccion, // Ahora controlado
                'tipo_entrega' => $_POST['tipo_entrega'],
                'fecha_entrega' => $_POST['fecha_entrega'],
                'ventana_entrega' => $_POST['ventana_entrega'],
                'subtotal_productos' => $subtotal,
                'costo_envio' => $costo_envio,
                'costo_total' => $subtotal + $costo_envio,
                'tipo_comprobante' => ($_SESSION['usuario_rol'] == 'EMPRESA_FACTURA') ? 'FACTURA' : 'BOLETA',
                'rol_cliente' => $_SESSION['usuario_rol']
            ];

            // Intentar crear
            $id_pedido = $this->pedidoModel->crear($datos_pedido, $productos_carrito);
            
            if ($id_pedido) {
                // ÉXITO
                $this->carritoModel->vaciarCarrito($_SESSION['usuario_id']);
                
                $data['success'] = 'Pedido creado exitosamente.';
                $data['id_pedido'] = $id_pedido;
                
                $this->redirect('pedido/historial');
            } else {
                // ERROR (El 'die' del modelo te dirá qué pasó antes de llegar aquí)
                $data['error'] = 'Error al procesar el pedido';
                $this->view('cliente_b2b/checkout', $data); 
            }
        }
    }

    

    public function detalle($id_pedido) {
        $this->requireAuth();
        
        // 1. Obtener información del pedido
        $pedido = $this->pedidoModel->obtenerPorId($id_pedido);
        
        // 2. Seguridad: Verificar que el pedido exista y pertenezca al usuario logueado
        if (!$pedido || $pedido['id_cliente'] != $_SESSION['usuario_id']) {
            $this->redirect('pedido/historial');
            return;
        }
        
        // 3. Obtener los productos
        $detalles = $this->pedidoModel->obtenerDetallesPedido($id_pedido);
        
        $data = [
            'pedido' => $pedido,
            'detalles' => $detalles
        ];
        
        $this->view('cliente_b2b/detalle_pedido', $data);
    }

    // API para AJAX: Recalcular totales en el checkout
    public function recalcularTotales() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tipo_entrega = $_POST['tipo_entrega'];
            $id_direccion = $_POST['id_direccion'] ?? null;

            $subtotal = $this->carritoModel->calcularTotal($_SESSION['usuario_id']);
            $costo_envio = 0;
            $mensaje_promo = '';
            $porcentaje_barra = 0;
            $es_gratis = false;

            if ($tipo_entrega === 'DOMICILIO' && $id_direccion) {
                // Obtenemos reglas de la zona
                $zona = $this->pedidoModel->obtenerDatosZona($id_direccion);
                
                if ($zona) {
                    $minimo = (float)$zona['monto_minimo_gratis'];
                    $costo_base = (float)$zona['costo_envio'];

                    if ($minimo > 0) {
                        if ($subtotal >= $minimo) {
                            // CASO 1: YA SUPERÓ EL MONTO
                            $costo_envio = 0;
                            $es_gratis = true;
                            $mensaje_promo = '<span class="text-success"><i class="fas fa-check-circle me-1"></i> ¡Genial! Tienes <strong>Envío Gratis</strong>.</span>';
                            $porcentaje_barra = 100;
                        } else {
                            // CASO 2: LE FALTA MONTO
                            $costo_envio = $costo_base;
                            $falta = $minimo - $subtotal;
                            $mensaje_promo = 'Agrega <strong>S/ ' . number_format($falta, 2) . '</strong> más para envío gratis.';
                            $porcentaje_barra = ($subtotal / $minimo) * 100;
                        }
                    } else {
                        // CASO 3: NO HAY PROMOCIÓN EN ESA ZONA
                        $costo_envio = $costo_base;
                        $mensaje_promo = '';
                        $porcentaje_barra = 0;
                    }
                } else {
                    $costo_envio = 10.00; // Fallback
                }
            }

            $total = $subtotal + $costo_envio;

            echo json_encode([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
                'costo_envio' => number_format($costo_envio, 2),
                'total' => number_format($total, 2),
                'es_gratis' => $es_gratis,
                'mensaje_promo' => $mensaje_promo,
                'porcentaje' => $porcentaje_barra
            ]);
            exit;
        }
    }
}
?>