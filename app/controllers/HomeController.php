<?php

class HomeController extends Controller {
    public function index() {
        if ($this->isLoggedIn()) {
            switch ($_SESSION['usuario_rol']) {
                case 'MAYORISTA_BOLETA':
                case 'EMPRESA_FACTURA':
                    $this->redirect('cliente/pedidoRapido');
                    break;
                case 'ADMIN':
                    $this->redirect('admin/');
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