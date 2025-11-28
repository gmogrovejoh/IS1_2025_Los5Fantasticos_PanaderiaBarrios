<?php

require_once(__DIR__ . '/../infrastructure/repositories/UsuarioRepositorio.php'); 

class StatusController {
    // Controla la petición status para validar health del sistema .
    
    private $repositorio;

    public function __construct() {
        // Inicializa el Repositorio.
        $this->repositorio = new UsuarioRepositorio();
    }

    public function checkStatus(): void {
        header('Content-Type: application/json');
        
        try {
            $adminExiste = $this->repositorio->verificarAdminExiste();
            
            $status = $adminExiste ? "OK" : "WARNING: Uusario no encontrado";
            $mensaje = $adminExiste ? "Baseline exitoso: Conexión y Roles verificados." : "Conexión a BD OK, pero falta el usuario inicial.";

            echo json_encode([
                "system_status" => $status,
                "message" => $mensaje,
                "db_check_ok" => $adminExiste
            ]);

        } catch (Exception $e) {
             http_response_code(500); // Manejo de errores fatales no capturados.
             echo json_encode([
                "system_status" => "ERROR",
                "message" => "Fallo interno en el servidor: " . $e->getMessage(),
                "db_check_ok" => false
            ]);
        }
    }
}

$controller = new StatusController();
$controller->checkStatus();