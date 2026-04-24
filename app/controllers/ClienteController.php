<?php

class ClienteController extends Controller {
    private $productoModel;
    private $carritoModel;

    public function __construct() {
        $this->productoModel = $this->model('Producto');
        $this->carritoModel = $this->model('Carrito');
    }

    public function pedidoRapido() {

        $this->requireRole(['MAYORISTA_BOLETA', 'EMPRESA_FACTURA']);
        
        $productos = $this->productoModel->obtenerCatalogoB2B();
        $data['productos'] = $productos;
        $this->view('cliente_b2b/pedido_rapido', $data);
    }

    public function carrito() {
        $this->requireAuth();

        if ($_SESSION['usuario_rol'] == 'ADMIN') {
            $this->redirect('admin/');
            return;
        }
        
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

    public function actualizarPassword() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $actual = $_POST['clave_actual'];
            $nueva = $_POST['clave_nueva'];
            $confirmar = $_POST['clave_confirmar'];

            // Validación básica
            if ($nueva !== $confirmar) {
                $_SESSION['mensaje_flash'] = ['tipo' => 'danger', 'texto' => 'Las nuevas contraseñas no coinciden.'];
            } elseif (strlen($nueva) < 6) {
                $_SESSION['mensaje_flash'] = ['tipo' => 'danger', 'texto' => 'La contraseña debe tener al menos 6 caracteres.'];
            } else {
                // Llamar al modelo
                $clienteModel = $this->model('Cliente');
                if ($clienteModel->cambiarContrasenia($_SESSION['usuario_id'], $actual, $nueva)) {
                    $_SESSION['mensaje_flash'] = ['tipo' => 'success', 'texto' => 'Contraseña actualizada correctamente.'];
                } else {
                    $_SESSION['mensaje_flash'] = ['tipo' => 'danger', 'texto' => 'La contraseña actual ingresada es incorrecta.'];
                }
            }
        }
        $this->redirect('cliente/perfil');
    }

    /**
     * Muestra el perfil del usuario
     */
    public function perfil() {
        $this->requireAuth();
        $clienteModel = $this->model('Cliente');
        $id_cliente = $_SESSION['usuario_id'];
        $mensaje = [];

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

        //Capturar mensaje flash de contraseña (si existe)
        if (isset($_SESSION['mensaje_flash'])) {
            $tipo = $_SESSION['mensaje_flash']['tipo'] == 'success' ? 'success' : 'error';
            $mensaje[$tipo] = $_SESSION['mensaje_flash']['texto'];
            unset($_SESSION['mensaje_flash']); // Limpiar mensaje
        }

        // Cargar vista
        $this->view('cliente_b2b/perfil', [
            'cliente' => $clienteModel->obtenerPorId($_SESSION['usuario_id']),
            'direcciones' => $clienteModel->obtenerDirecciones($_SESSION['usuario_id']),
            'distritos' => $clienteModel->obtenerDistritos(),
            'mensaje' => $mensaje
        ]);
    }
}
?>