<?php


require_once '../app/core/Controller.php';

class AuthController extends Controller {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = $this->model('Cliente');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $contrasenia = $_POST['contrasenia'];

            if (empty($email) || empty($contrasenia)) {
                $data['error'] = 'Por favor complete todos los campos';
                $this->view('auth/login', $data);
                return;
            }

            $usuario = $this->clienteModel->login($email, $contrasenia);
            
            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id_cliente'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_apellidos'] = $usuario['apellidos'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_rol'] = $usuario['rol'];

                // Redirigir según el rol
                switch ($usuario['rol']) {
                    case 'ADMIN':
                        $this->redirect('admin/dashboard');
                        break;
                    case 'CLIENTE_ESTANDAR':
                        $this->redirect('cliente/catalogo');
                        break;
                    case 'MAYORISTA_BOLETA':
                    case 'EMPRESA_FACTURA':
                        $this->redirect('cliente/dashboard');
                        break;
                }
            } else {
                $data['error'] = 'Credenciales incorrectas';
                $this->view('auth/login', $data);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombre' => filter_var($_POST['nombre'], FILTER_SANITIZE_STRING),
                'apellidos' => filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING),
                'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'telefono' => filter_var($_POST['telefono'], FILTER_SANITIZE_STRING),
                'contrasenia' => $_POST['contrasenia']
            ];

            // Validaciones básicas
            if (empty($datos['nombre']) || empty($datos['apellidos']) || 
                empty($datos['email']) || empty($datos['contrasenia'])) {
                $data['error'] = 'Por favor complete todos los campos obligatorios';
                $this->view('auth/registro', $data);
                return;
            }

            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Email inválido';
                $this->view('auth/registro', $data);
                return;
            }

            $cliente_id = $this->clienteModel->registrar($datos);
            
            if ($cliente_id) {
                $data['success'] = 'Registro exitoso. Ya puede iniciar sesión.';
                $this->view('auth/login', $data);
            } else {
                $data['error'] = 'Error al registrar usuario. El email podría estar en uso.';
                $this->view('auth/registro', $data);
            }
        } else {
            $this->view('auth/registro');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}
?>