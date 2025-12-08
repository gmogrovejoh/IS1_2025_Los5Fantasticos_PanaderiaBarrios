<?php

class ClienteController extends Controller {
    private $productoModel;
    private $carritoModel;

    public function __construct() {
        $this->productoModel = $this->model('Producto');
        $this->carritoModel = $this->model('Carrito');
    }

    public function catalogo() {
        // Si alguien intenta entrar aquí, lo mandamos al dashboard B2B
        $this->redirect('cliente/pedidoRapido');
    }

    // En la función index() o dashboard():
    public function dashboard() {
        $this->requireAuth();
        // Redirigir directamente al pedido rápido, que es lo más útil para B2B
        $this->redirect('cliente/pedidoRapido');
    }

    public function pedidoRapido() {

        $this->requireRole(['MAYORISTA_BOLETA', 'EMPRESA_FACTURA']);
        
        $productos = $this->productoModel->obtenerCatalogoB2B();
        $data['productos'] = $productos;
        $this->view('cliente_b2b/pedido_rapido', $data);
    }

    public function carrito() {
        $this->requireAuth();
        
        // CORRECCIÓN: Cambiamos 'obtenerProductosCarrito' por 'obtenerProductos'
        $productos_carrito = $this->carritoModel->obtenerProductos($_SESSION['usuario_id']);
        
        // CORRECCIÓN: 'calcularSubtotal' ahora se llama 'calcularTotal' en el modelo nuevo
        $subtotal = $this->carritoModel->calcularTotal($_SESSION['usuario_id']);
        
        $data['productos'] = $productos_carrito;
        $data['subtotal'] = $subtotal;
        
        // Usamos la vista unificada B2B/B2C
        $this->view('cliente_b2b/carrito', $data);
    }

    public function checkout() {
        $this->requireAuth();
        
        // CORRECCIÓN: Igual aquí, actualizamos el nombre de la función
        $productos_carrito = $this->carritoModel->obtenerProductos($_SESSION['usuario_id']);
        
        if (empty($productos_carrito)) {
            $this->redirect('cliente/carrito');
        }

        // CORRECCIÓN: Actualizamos nombre de calcularTotal
        $subtotal = $this->carritoModel->calcularTotal($_SESSION['usuario_id']);
        
        $data['productos'] = $productos_carrito;
        $data['subtotal'] = $subtotal;
        
        // Obtener direcciones (solo si es B2B, aunque tu lógica nueva es 100% B2B)
        $clienteModel = $this->model('Cliente');
        $data['direcciones'] = $clienteModel->obtenerDirecciones($_SESSION['usuario_id']);
        $data['cliente'] = [
            'nombre' => $_SESSION['usuario_nombre'],
            'apellidos' => $_SESSION['usuario_apellidos'],
            'email' => $_SESSION['usuario_email']
        ];
        
        $this->view('cliente_b2b/checkout', $data);
    }

    public function perfil() {
        $this->requireAuth();
        $clienteModel = $this->model('Cliente');
        $id_cliente = $_SESSION['usuario_id'];
        $mensaje = [];

        // 1. Lógica para Actualizar Datos Personales
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_perfil'])) {
            $datos = [
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'telefono' => $_POST['telefono'],
                'ruc' => $_POST['ruc'],
                'razon_social' => $_POST['razon_social']
            ];
            
            if ($clienteModel->actualizarInformacion($id_cliente, $datos)) {
                // Actualizamos sesión para reflejar cambios inmediatos
                $_SESSION['usuario_nombre'] = $datos['nombre'];
                $_SESSION['usuario_apellidos'] = $datos['apellidos'];
                $mensaje['success'] = 'Información actualizada correctamente.';
            } else {
                $mensaje['error'] = 'Error al actualizar información.';
            }
        }

        // Obtener datos frescos
        $data['cliente'] = $clienteModel->obtenerPorId($id_cliente);
        $data['direcciones'] = $clienteModel->obtenerDirecciones($id_cliente);
        $data['distritos'] = $clienteModel->obtenerDistritos(); // Para el select
        $data['mensaje'] = $mensaje;

        $this->view('cliente_b2b/perfil', $data);
    }

    public function guardarDireccion() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clienteModel = $this->model('Cliente');
            
            $datos = [
                'alias' => $_POST['alias'],
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'referencia' => $_POST['referencia'],
                'id_distrito' => $_POST['id_distrito']
            ];

            $clienteModel->agregarDireccion($_SESSION['usuario_id'], $datos);
        }
        $this->redirect('cliente/perfil');
    }

    public function eliminarDireccion() {
        $this->requireAuth();
        if (isset($_POST['id_direccion'])) {
            $clienteModel = $this->model('Cliente');
            $clienteModel->eliminarDireccion($_POST['id_direccion'], $_SESSION['usuario_id']);
        }
        $this->redirect('cliente/perfil');
    }
}
?>