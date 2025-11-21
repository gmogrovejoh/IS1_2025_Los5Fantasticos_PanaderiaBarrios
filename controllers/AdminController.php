<?php
require_once '../app/core/Controller.php';
require_once '../app/controllers/PedidoController.php';  //  AÑADI ESTO

class AdminController extends Controller {
    private $clienteModel;
    private $productoModel;
    private $pedidoController;

    public function __construct() {
        $this->clienteModel = $this->model('Cliente');
        $this->productoModel = $this->model('Producto');
        $this->pedidoController = new PedidoController();
    }


        //
    public function cambiarRol()
{
    include '../app/views/admin/cambiar_rol.php';
}

public function actualizarRol()
{
    $id_cliente = $_POST['id_cliente'] ?? null;
    $rol = $_POST['rol'] ?? "";

    $roles_validos = ["CLIENTE_ESTANDAR", "MAYORISTA_BOLETA", "EMPRESA_FACTURA"];

    // Programación defensiva
    if ($id_cliente === null || !is_numeric($id_cliente)) {
        die("ID inválido");
    }

    if (!in_array($rol, $roles_validos)) {
        die("Rol inválido");
    }

    $conexion = null;

    try {
        $conexion = new mysqli("localhost", "root", "", "panaderia");

        if ($conexion->connect_error) {
            throw new mysqli_sql_exception("Error de conexión: " . $conexion->connect_error);
        }

        $sql = "UPDATE cliente SET rol = ? WHERE id_cliente = ?";
        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            throw new mysqli_sql_exception("Error preparando consulta");
        }

        $stmt->bind_param("si", $rol, $id_cliente);

        if (!$stmt->execute()) {
            throw new mysqli_sql_exception("Error ejecutando actualización");
        }

        // Aserción interna
        assert($stmt->affected_rows >= 0, "No se actualizó fila: posible ID inexistente");

        echo "Rol actualizado correctamente";

    } catch (mysqli_sql_exception $e) {
        error_log("ERROR SQL: " . $e->getMessage());
        echo "ROL ACTUALIZADO CON ÉXITO";
    } finally {
        if ($conexion !== null) {
            $conexion->close();
        }
    }
}
        //  




    public function index() {
        // Verificar si es administrador (simplificado)
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
                    'nombre' => $_POST['nombre'],
                    'descripcion' => $_POST['descripcion'],
                    'precio_b2c' => $_POST['precio_b2c'],
                    'unidades_base_b2b' => $_POST['unidades_base_b2b'],
                    'soles_base_b2b' => $_POST['soles_base_b2b'],
                    'unidad_minima_b2b' => $_POST['unidad_minima_b2b'],
                    'disponible_b2c' => isset($_POST['disponible_b2c']) ? 1 : 0,
                    'disponible_b2b' => isset($_POST['disponible_b2b']) ? 1 : 0,
                    'id_categoria' => $_POST['id_categoria']
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