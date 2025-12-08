<?php
// app/core/Controller.php


require 'vendor/autoload.php';

use OpenApi\Generator;

// LISTA MÁGICA:
// 1. La configuración global.
// 2. La clase Padre (Controller) para que no se salte a los hijos.
// 3. Tu controlador API.
$files_to_scan = [
    'app/swagger-config.php',
    'app/core/Controller.php',
    'app/controllers/ApiProductoController.php'
];

$openapi = Generator::scan($files_to_scan);

file_put_contents('public/swagger.json', $openapi->toJson());

echo "¡Documentación generada con éxito! Verifica en el navegador.\n";


class Controller {
    protected function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    protected function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }

    protected function requireAuth() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }
    }

    protected function requireRole($roles) {
        $this->requireAuth();
        if (!in_array($_SESSION['usuario_rol'], $roles)) {
            $this->redirect('');
        }
    }

    protected function isLoggedIn() {
        return isset($_SESSION['usuario_id']);
    }
}
?>