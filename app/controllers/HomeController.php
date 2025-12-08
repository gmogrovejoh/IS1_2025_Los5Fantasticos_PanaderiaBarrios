<?php

class HomeController extends Controller {
    public function index() {
        if ($this->isLoggedIn()) {
            // Redirigir según el rol del usuario
            switch ($_SESSION['usuario_rol']) {
                case 'CLIENTE_ESTANDAR':
                    $this->redirect('cliente/catalogo');
                    break;
                case 'MAYORISTA_BOLETA':
                case 'EMPRESA_FACTURA':
                    $this->redirect('cliente/dashboard');
                    break;
                default:
                    $this->redirect('auth/login');
            }
        } else {
            $this->redirect('auth/login');
        }
    }
}
?>