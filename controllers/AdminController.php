<?php
require_once '../app/core/Controller.php';
require_once '../app/controllers/PedidoController.php';

class AdminController extends Controller {
    private $clienteModel;
    private $productoModel;
    private $pedidoController;

    public function __construct() {
        $this->clienteModel = $this->model('Cliente');
        $this->productoModel = $this->model('Producto');
        $this->pedidoController = new PedidoController();
    }

    public function index() {
        $this->requireAuth();
        $this->view('admin/dashboard');
    }

    public function gestionClientes() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['actualizar_rol'])) {
                $id_cliente = (int)$_POST['id_cliente'];
                $nuevo_rol = $_POST['rol'];
                
                $this->clienteModel->actualizarRol($id_cliente, $nuevo_rol);
                $data['success'] = 'Rol actualizado correctamente';
            }
            
            if (isset($_POST['actualizar_empresa'])) {
                $id_cliente = (int)$_POST['id_cliente'];
                $ruc = $_POST['ruc'];
                $razon_social = $_POST['razon_social'];
                
                $this->clienteModel->actualizarDatosEmpresa($id_cliente, $ruc, $razon_social);
                $data['success'] = 'Datos de empresa actualizados correctamente';
            }
        }
        
        $clientes = $this->clienteModel->obtenerTodos();
        $data['clientes'] = $clientes;
        
        $this->view('admin/gestion_clientes', $data);
    }

    public function gestionProductos() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['crear_producto'])) {
                $datos = [
                    ':nombre' => $_POST['nombre'],
                    ':descripcion' => $_POST['descripcion'],
                    //':foto' => $_POST['foto'] ?? null,
                    ':precio_b2c' => $_POST['precio_b2c'],
                    ':unidades_base_b2b' => $_POST['unidades_base_b2b'],
                    ':soles_base_b2b' => $_POST['soles_base_b2b'],
                    ':unidad_minima_b2b' => $_POST['unidad_minima_b2b'],
                    ':disponible_b2c' => isset($_POST['disponible_b2c']) ? 1 : 0,
                    ':disponible_b2b' => isset($_POST['disponible_b2b']) ? 1 : 0,
                    ':id_categoria' => $_POST['id_categoria']
                ];
                
                $this->productoModel->crear($datos);
                $data['success'] = 'Producto creado correctamente';
            }
        }
        
        $productos = $this->productoModel->obtenerTodos();
        $data['productos'] = $productos;
        
        $this->view('admin/gestion_productos', $data);
    }

    public function hojaProduccion() {
        $this->requireAuth();
        
        $data = $this->pedidoController->hojaProduccion();
        $this->view('admin/hoja_produccion', $data);
    }
}
?>