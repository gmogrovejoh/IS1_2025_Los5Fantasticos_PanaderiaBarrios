<?php

class Conexion {
    
    private static $instancia = null;
    
    private $db_name = 'PanaderiaBarriosDB'; 
    
    private $db_user = 'root'; 
    
    private $db_pass = 'fanwhy1'; 

    private function __construct() {
    }

    public static function getInstancia(): PDO {
        // Devuelve la única instancia de conexión PDO.
        if (self::$instancia === null) {
            $db = new self();
            try {
                $pdo = new PDO(
                    "mysql:host=localhost;dbname={$db->db_name}",
                    $db->db_user,
                    $db->db_pass
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instancia = $pdo;
                
            } catch (PDOException $e) {
                // Detiene la ejecución si el servidor MySQL no responde.
                die("ERROR DE CONEXIÓN: La Base de Datos no está activa o las credenciales en Conexion.php son incorrectas. Mensaje: " . $e->getMessage());
            }
        }
        return self::$instancia;
    }
}