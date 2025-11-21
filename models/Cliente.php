<?php
require_once '../app/core/Database.php';

class Cliente {
    private $conn;
    private $table = 'cliente';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function registrar($datos) {
        $query = "INSERT INTO " . $this->table . " (nombre, apellidos, email, telefono, contrasenia, rol) 
                  VALUES (:nombre, :apellidos, :email, :telefono, :contrasenia, 'CLIENTE_ESTANDAR')";
        
        $stmt = $this->conn->prepare($query);
        
        $datos['contrasenia'] = password_hash($datos['contrasenia'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellidos', $datos['apellidos']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':contrasenia', $datos['contrasenia']);
        
        if ($stmt->execute()) {
            $cliente_id = $this->conn->lastInsertId();
            // Crear carrito automáticamente
            $this->crearCarrito($cliente_id);
            return $cliente_id;
        }
        return false;
    }

    public function login($email, $contrasenia) {
        $query = "SELECT id_cliente, nombre, apellidos, email, contrasenia, rol FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($contrasenia, $row['contrasenia'])) {
                return $row;
            }
        }
        return false;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_registro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarRol($id_cliente, $rol) {
        $query = "UPDATE " . $this->table . " SET rol = :rol WHERE id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':id_cliente', $id_cliente);
        return $stmt->execute();
    }

    public function actualizarDatosEmpresa($id_cliente, $ruc, $razon_social) {
        $query = "UPDATE " . $this->table . " SET ruc = :ruc, razon_social = :razon_social WHERE id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ruc', $ruc);
        $stmt->bindParam(':razon_social', $razon_social);
        $stmt->bindParam(':id_cliente', $id_cliente);
        return $stmt->execute();
    }

    private function crearCarrito($cliente_id) {
        $query = "INSERT INTO carrito (id_cliente) VALUES (:id_cliente)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $cliente_id);
        return $stmt->execute();
    }

    public function obtenerDirecciones($id_cliente) {
        $query = "SELECT d.*, dist.nombre as distrito_nombre 
                  FROM direccion d 
                  JOIN distrito dist ON d.id_distrito = dist.id_distrito 
                  WHERE d.id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>