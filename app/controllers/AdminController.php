<?php

class AdminController extends Controller {
    private $clienteModel;
    private $productoModel;
    private $pedidoModel;

    public function __construct() {
        // Verificar que sea ADMIN antes de nada
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'ADMIN') {
            $this->redirect('auth/login');
        }

        $this->clienteModel = $this->model('Cliente');
        $this->productoModel = $this->model('Producto');
        $this->pedidoModel = $this->model('Pedido');
    }

    public function index() {
        // Dashboard Principal: Estadísticas
        $data['stats'] = [
            'clientes' => count($this->clienteModel->obtenerTodos()),
            'productos' => count($this->productoModel->obtenerTodos()),
            'pedidos_hoy' => 0 
        ];
        $this->view('admin/dashboard', $data);
    }

    // --- GESTIÓN DE PRODUCTOS ---

    public function gestionProductos() {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // LOGICA DE SUBIDA DE IMAGEN
            $foto = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $nombre_archivo = time() . '.' . $ext; // Nombre único
                $ruta = '../public/img/' . $nombre_archivo;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)) {
                    $foto = $nombre_archivo;
                }
            } else {
                // Si es edición y no suben foto nueva, mantenemos la vieja
                $foto = $_POST['foto_actual'] ?? null;
            }

            $datos = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio_b2c' => $_POST['precio_b2c'],
                'unidades_base_b2b' => $_POST['unidades_base_b2b'] ?: 0,
                'soles_base_b2b' => $_POST['soles_base_b2b'] ?: 0,
                'unidad_minima_b2b' => $_POST['unidad_minima_b2b'] ?: 1,
                'disponible_b2b' => isset($_POST['disponible_b2b']) ? 1 : 0,
                'id_categoria' => $_POST['id_categoria'],
                'foto' => $foto
            ];

            if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
                if ($this->productoModel->crear($datos)) {
                    $success = "Producto creado correctamente.";
                } else {
                    $error = "Error al crear producto.";
                }
            } elseif (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
                $id = $_POST['id_producto'];
                if ($this->productoModel->actualizar($id, $datos)) {
                    $success = "Producto actualizado correctamente.";
                } else {
                    $error = "Error al actualizar.";
                }
            }
        }

        // Obtener lista actualizada
        $productos = $this->productoModel->obtenerTodos();
        $categorias = $this->productoModel->obtenerCategorias(); // Necesitas crear este método en el modelo

        $this->view('admin/gestion_productos', [
            'productos' => $productos,
            'categorias' => $categorias,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function eliminarProducto($id) {
        // Método simple para borrar (llamado vía GET o POST desde JS)
        $this->productoModel->eliminar($id);
        $this->redirect('admin/gestionProductos');
    }

    // --- GESTIÓN DE PEDIDOS ---

    public function gestionPedidos() {
        // Filtrar por estado si se envía, sino todos
        $estado = $_GET['estado'] ?? null;
        $fecha = $_GET['fecha'] ?? null;

        $pedidos = $this->pedidoModel->obtenerTodosAdmin($estado, $fecha);
        
        $this->view('admin/gestion_pedidos', ['pedidos' => $pedidos]);
    }

    public function detallePedido($id) {
        $pedido = $this->pedidoModel->obtenerPorId($id);
        $detalles = $this->pedidoModel->obtenerDetallesPedido($id);
        
        $this->view('admin/detalle_pedido', ['pedido' => $pedido, 'detalles' => $detalles]);
    }

    public function cambiarEstadoPedido() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_pedido = $_POST['id_pedido'];
            $nuevo_estado = $_POST['estado'];
            
            $this->pedidoModel->actualizarEstado($id_pedido, $nuevo_estado);
            $this->redirect('admin/gestionPedidos');
        }
    }

    // --- HOJA DE PRODUCCIÓN ---
    
    public function hojaProduccion() {
        $fecha = $_GET['fecha'] ?? date('Y-m-d', strtotime('+1 day')); // Por defecto mañana
        $ventana = $_GET['ventana'] ?? 'MAÑANA';

        $produccion = $this->pedidoModel->calcularProduccionTotal($fecha, $ventana);

        $this->view('admin/hoja_produccion', [
            'fecha' => $fecha,
            'ventana' => $ventana,
            'produccion' => $produccion
        ]);
    }

    

    // ... dentro de AdminController ...

    public function eliminarPedido($id) {
        if ($this->pedidoModel->eliminar($id)) {
            // Podrías pasar un mensaje de éxito por sesión aquí si tuvieras un sistema de flash messages
            $this->redirect('admin/gestionPedidos');
        } else {
            // Manejo de error
            $this->redirect('admin/gestionPedidos');
        }
    }

    public function actualizarPedido() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_pedido = $_POST['id_pedido'];
            $estado = $_POST['estado'];
            $fecha_entrega = $_POST['fecha_entrega'];
            $ventana_entrega = $_POST['ventana_entrega']; // <--- Nuevo dato capturado

            // Pasamos los 4 argumentos al modelo
            $this->pedidoModel->actualizarDatosAdmin($id_pedido, $estado, $fecha_entrega, $ventana_entrega);
            
            // Recargar la página
            $this->redirect('admin/detallePedido/' . $id_pedido);
        }
    }

    public function nuevoPedido() {
        // 1. Obtener datos necesarios
        $clientes = $this->clienteModel->obtenerTodos();
        $productos = $this->productoModel->obtenerCatalogoB2B(); // O obtenerTodos()

        $this->view('admin/nuevo_pedido', [
            'clientes' => $clientes,
            'productos' => $productos
        ]);
    }


    public function registrarPedido() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // --- NUEVA VALIDACIÓN ---
            $fecha_solicitada = $_POST['fecha_entrega'];
            $fecha_minima = date('Y-m-d', strtotime('+1 day ,-5 hour'));

            if ($fecha_solicitada < $fecha_minima) {
                // Como es admin, podemos ser más directos con el error o redirigir
                // Para simplificar, redirigimos de vuelta con un parámetro de error
                echo "<script>alert('Error: La fecha debe ser a partir de mañana para entrar en producción.'); window.history.back();</script>";
                return;
            }
            // 1. Recopilar datos básicos
            $id_cliente = $_POST['id_cliente'];
            $tipo_entrega = $_POST['tipo_entrega'];
            $id_direccion = ($tipo_entrega == 'DOMICILIO') ? $_POST['id_direccion'] : null;
            
            // 2. Procesar productos seleccionados
            // Vienen en formato array: cantidades[id_producto] = cantidad
            $items_procesados = [];
            $subtotal_global = 0;
            $cantidades = $_POST['cantidades'] ?? [];

            foreach ($cantidades as $id_producto => $cantidad) {
                if ($cantidad > 0) {
                    $producto = $this->productoModel->obtenerPorId($id_producto);
                    
                    // Calcular precio unitario (Lógica B2B)
                    if ($producto['unidades_base_b2b'] > 0 && $producto['soles_base_b2b'] > 0) {
                        $precio_unit = $producto['soles_base_b2b'] / $producto['unidades_base_b2b'];
                    } else {
                        $precio_unit = $producto['precio_b2c'];
                    }

                    $subtotal_linea = $cantidad * $precio_unit;
                    $subtotal_global += $subtotal_linea;

                    // Estructura que espera el Modelo Pedido
                    $items_procesados[] = [
                        'id_producto' => $id_producto,
                        'cantidad' => $cantidad,
                        'unidades_base_b2b' => $producto['unidades_base_b2b'],
                        'soles_base_b2b' => $producto['soles_base_b2b'],
                        'precio_b2c' => $producto['precio_b2c']
                    ];
                }
            }

            if (empty($items_procesados)) {
                // Error: No seleccionó productos
                $this->redirect('admin/nuevoPedido');
                return;
            }

            // 3. Calcular Envío (Usamos el modelo Pedido para la lógica)
            $costo_envio = 0;
            if ($tipo_entrega == 'DOMICILIO' && $id_direccion) {
                $costo_envio = $this->pedidoModel->calcularCostoEnvio($id_direccion, $subtotal_global);
            }

            // 4. Preparar array final
            $datos_pedido = [
                'id_cliente' => $id_cliente,
                'id_sede' => 1,
                'id_direccion_entrega' => $id_direccion,
                'tipo_entrega' => $tipo_entrega,
                'fecha_entrega' => $_POST['fecha_entrega'],
                'ventana_entrega' => $_POST['ventana_entrega'],
                'subtotal_productos' => $subtotal_global,
                'costo_envio' => $costo_envio,
                'costo_total' => $subtotal_global + $costo_envio,
                'tipo_comprobante' => $_POST['tipo_comprobante'],
                'rol_cliente' => 'ADMIN_CREATED' // Marca interna
            ];

            // 5. Guardar
            $id = $this->pedidoModel->crear($datos_pedido, $items_procesados);

            if ($id) {
                // Redirigir al detalle del nuevo pedido
                $this->redirect('admin/detallePedido/' . $id);
            } else {
                echo "Error al crear pedido";
            }
        }
    }

    
    
    // API para obtener direcciones (Ya la creamos antes, la reusamos)
    public function apiDirecciones($id_cliente) {
        $direcciones = $this->clienteModel->obtenerDirecciones($id_cliente);
        echo json_encode($direcciones);
    }

    public function gestionClientes() {
        $mensaje = [];

        // LÓGICA POST: Crear o Editar
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
            
            // 1. CASO EDITAR
            if ($_POST['accion'] == 'editar_cliente') {
                $datos = [
                    'nombre' => $_POST['nombre'],
                    'apellidos' => $_POST['apellidos'],
                    'email' => $_POST['email'],
                    'telefono' => $_POST['telefono'],
                    'ruc' => $_POST['ruc'],
                    'razon_social' => $_POST['razon_social'],
                    'rol' => $_POST['rol']
                ];
                
                // Llamamos al modelo (asegúrate de que actualizarPorAdmin exista en Cliente.php)
                if ($this->clienteModel->actualizarPorAdmin($_POST['id_cliente'], $datos)) {
                    $mensaje['success'] = "Cliente actualizado correctamente.";
                } else {
                    $mensaje['error'] = "Error al actualizar (posible email duplicado).";
                }
            }
            
            // 2. CASO CREAR (Si también quieres reactivarlo)
            elseif ($_POST['accion'] == 'crear_cliente') {
                // ... tu lógica de crear ...
                 $datos = [
                    'nombre' => $_POST['nombre'],
                    'apellidos' => $_POST['apellidos'],
                    'email' => $_POST['email'],
                    'telefono' => $_POST['telefono'],
                    'ruc' => $_POST['ruc'],
                    'razon_social' => $_POST['razon_social'],
                    'rol' => $_POST['rol'],
                    'contrasenia' => '123456' // Pass por defecto
                ];
                if ($this->clienteModel->crearPorAdmin($datos)) {
                    $mensaje['success'] = "Cliente creado (Pass: 123456).";
                } else {
                    $mensaje['error'] = "Error al crear cliente.";
                }
            }

            // 3. CASO NUEVA DIRECCIÓN (Si lo usas)
            elseif ($_POST['accion'] == 'nueva_direccion_admin') {
                $this->clienteModel->agregarDireccion($_POST['id_cliente_dir'], $_POST);
                $mensaje['success'] = "Dirección agregada.";
            }
        }

        // LÓGICA GET: Eliminar
        if (isset($_GET['eliminar'])) {
            $this->clienteModel->eliminar($_GET['eliminar']);
            $this->redirect('admin/gestionClientes'); // Recargar para limpiar URL
        }

        // CARGAR VISTA
        $this->view('admin/gestion_clientes', [
            'clientes' => $this->clienteModel->obtenerTodos(),
            'distritos' => $this->clienteModel->obtenerDistritos(),
            'mensaje' => $mensaje
        ]);
    }
}
?>
