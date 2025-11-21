<?php
require_once '../app/core/Controller.php';

class ClienteController extends Controller {
    private $productoModel;
    private $carritoModel;

    public function __construct() {
        $this->productoModel = $this->model('Producto');
        $this->carritoModel = $this->model('Carrito');
    }

    public function catalogo() {
        $this->requireAuth();
        
        if ($_SESSION['usuario_rol'] == 'CLIENTE_ESTANDAR') {
            // Catálogo B2C
            $productos = $this->productoModel->obtenerCatalogoB2C();
            
            // Calcular ahorros para packs
            foreach ($productos as &$producto) {
                if ($this->productoModel->esPack($producto['id_producto'])) {
                    $precio_separado = $this->productoModel->calcularPrecioSeparado($producto['id_producto']);
                    $producto['precio_separado'] = $precio_separado;
                    $producto['ahorro'] = $precio_separado - $producto['precio_b2c'];
                }
            }
            
            $data['productos'] = $productos;
            $data['es_b2c'] = true;
            $this->view('cliente_b2c/catalogo', $data);
        } else {
            $this->redirect('cliente/dashboard');
        }
    }

    public function dashboard() {
        $this->requireRole(['MAYORISTA_BOLETA', 'EMPRESA_FACTURA']);
        $this->view('cliente_b2b/dashboard');
    }

    public function pedidoRapido() {
        $this->requireRole(['MAYORISTA_BOLETA', 'EMPRESA_FACTURA']);
        
        $productos = $this->productoModel->obtenerCatalogoB2B();
        $data['productos'] = $productos;
        $this->view('cliente_b2b/pedido_rapido', $data);
    }

    public function carrito() {
        $this->requireAuth();
        
        $productos_carrito = $this->carritoModel->obtenerProductosCarrito($_SESSION['usuario_id']);
        $subtotal = $this->carritoModel->calcularSubtotal($_SESSION['usuario_id'], $_SESSION['usuario_rol']);
        
        $data['productos'] = $productos_carrito;
        $data['subtotal'] = $subtotal;
        $data['es_b2c'] = ($_SESSION['usuario_rol'] == 'CLIENTE_ESTANDAR');
        
        if ($_SESSION['usuario_rol'] == 'CLIENTE_ESTANDAR') {
            $this->view('cliente_b2c/carrito', $data);
        } else {
            $this->view('cliente_b2b/carrito', $data);
        }
    }

    public function checkout() {
        $this->requireAuth();
        
        $productos_carrito = $this->carritoModel->obtenerProductosCarrito($_SESSION['usuario_id']);
        if (empty($productos_carrito)) {
            $this->redirect('cliente/carrito');
        }

        $subtotal = $this->carritoModel->calcularSubtotal($_SESSION['usuario_id'], $_SESSION['usuario_rol']);
        
        $data['productos'] = $productos_carrito;
        $data['subtotal'] = $subtotal;
        $data['es_b2c'] = ($_SESSION['usuario_rol'] == 'CLIENTE_ESTANDAR');
        
        // Obtener direcciones para B2B
        if ($_SESSION['usuario_rol'] != 'CLIENTE_ESTANDAR') {
            $clienteModel = $this->model('Cliente');
            $data['direcciones'] = $clienteModel->obtenerDirecciones($_SESSION['usuario_id']);
        }
        
        if ($_SESSION['usuario_rol'] == 'CLIENTE_ESTANDAR') {
            $this->view('cliente_b2c/checkout', $data);
        } else {
            $this->view('cliente_b2b/checkout', $data);
        }
    }
}
?>