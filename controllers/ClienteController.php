<?php
class ClienteController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $contrasenia = $_POST['contrasenia'] ?? '';
            
            $clienteModel = new Cliente();
            $cliente = $clienteModel->login($email, $contrasenia);
            
            if ($cliente) {
                $_SESSION['cliente'] = $cliente;
                header('Location: index.php');
                exit;
            } else {
                $error = "Credenciales incorrectas";
            }
        }
        
        include 'views/cliente/login.php';
    }
    
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellidos' => $_POST['apellidos'] ?? '',
                'email' => $_POST['email'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'contrasenia' => $_POST['contrasenia'] ?? ''
            ];
            
            // Validaciones básicas
            if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['contrasenia'])) {
                $error = "Por favor complete todos los campos obligatorios";
            } else {
                $clienteModel = new Cliente();
                
                if ($clienteModel->registrar($datos)) {
                    $success = "Registro exitoso. Ahora puede iniciar sesión.";
                } else {
                    $error = "Error al registrar usuario";
                }
            }
        }
        
        include 'views/cliente/registro.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    public function perfil() {
        if (!isset($_SESSION['cliente'])) {
            header('Location: index.php?controller=cliente&action=login');
            exit;
        }
        
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPorCliente($_SESSION['cliente']['id_cliente']);
        
        include 'views/cliente/perfil.php';
    }
}
?>