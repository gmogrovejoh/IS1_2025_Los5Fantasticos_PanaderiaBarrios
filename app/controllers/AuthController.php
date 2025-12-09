<?php

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
                if ($usuario['rol'] == 'CLIENTE_ESTANDAR') {
                    $data['error'] = 'Esta plataforma es exclusiva para Mayoristas y Empresas.';
                    $this->view('auth/login', $data);
                    return;
                }
                $_SESSION['usuario_id'] = $usuario['id_cliente'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_apellidos'] = $usuario['apellidos'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_rol'] = $usuario['rol'];

                // Redirigir según el rol
                switch ($usuario['rol']) {
                    case 'ADMIN':
                        $this->redirect('admin/');
                        break;
                    case 'MAYORISTA_BOLETA':
                    case 'EMPRESA_FACTURA':
                        $this->redirect('cliente/pedidoRapido'); // <--- Redirección directa
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
                'nombre' => filter_var($_POST['nombre'], FILTER_SANITIZE_SPECIAL_CHARS),
                'apellidos' => filter_var($_POST['apellidos'], FILTER_SANITIZE_SPECIAL_CHARS),
                'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'telefono' => filter_var($_POST['telefono'], FILTER_SANITIZE_SPECIAL_CHARS),
                'ruc' => filter_var($_POST['ruc'], FILTER_SANITIZE_SPECIAL_CHARS), // Nuevo campo obligatorio
                'razon_social' => filter_var($_POST['razon_social'], FILTER_SANITIZE_SPECIAL_CHARS), // Nuevo campo obligatorio
                'contrasenia' => $_POST['contrasenia']
            ];

            // Validaciones
            if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['ruc']) || empty($datos['razon_social'])) {
                $data['error'] = 'El nombre, email, RUC y Razón Social son obligatorios.';
                $this->view('auth/registro', $data);
                return;
            }

            // Llamar al modelo (necesitaremos actualizar el modelo también)
            // Por defecto lo registramos como MAYORISTA_BOLETA o EMPRESA_FACTURA
            $cliente_id = $this->clienteModel->registrarB2B($datos);
            
            if ($cliente_id) {
                $data['success'] = 'Registro exitoso. Bienvenido a la plataforma Mayorista.';
                $this->view('auth/login', $data);
            } else {
                $data['error'] = 'Error al registrar. El email o RUC podría estar en uso.';
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