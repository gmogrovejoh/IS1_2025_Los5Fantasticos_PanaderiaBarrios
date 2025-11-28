<?php

require_once(__DIR__ . '/../../config/Conexion.php'); 

class UsuarioRepositorio {// Gestiona el acceso a la tabla 'cliente'.
    
    private $db;

    public function __construct() {
        $this->db = Conexion::getInstancia();
    }

    public function verificarAdminExiste(): bool {
        // Verifica la existencia del rol de nivel superior (Baseline).
        $sql = "SELECT COUNT(*) FROM cliente WHERE rol = 'EMPRESA_FACTURA'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }
}