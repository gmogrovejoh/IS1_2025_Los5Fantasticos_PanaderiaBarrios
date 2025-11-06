<?php
class Cliente {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function registrar($datos) {
        $sql = "INSERT INTO cliente (nombre, apellidos, email, dni, telefono, contrasenia) 
                VALUES (:nombre, :apellidos, :email, :dni, :telefono, :contrasenia)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellidos', $datos['apellidos']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':dni', $datos['dni']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':contrasenia', password_hash($datos['contrasenia'], PASSWORD_DEFAULT));
        
        return $stmt->execute();
    }
    
    public function login($email, $contrasenia) {
        $sql = "SELECT * FROM cliente WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $cliente = $stmt->fetch();
        
        if ($cliente && password_verify($contrasenia, $cliente['contrasenia'])) {
            return $cliente;
        }
        
        return $cliente;
    }
    
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function actualizarPuntos($id_cliente, $puntos) {
        $sql = "UPDATE cliente SET puntos = puntos + :puntos WHERE id_cliente = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':puntos', $puntos);
        $stmt->bindParam(':id', $id_cliente);
        
        return $stmt->execute();
    }
}
?>