<?php

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

    public function registrarB2B($datos) {
        // Definimos el rol por defecto para nuevos registros (ej. EMPRESA_FACTURA)
        $query = "INSERT INTO " . $this->table . " 
                (nombre, apellidos, email, telefono, ruc, razon_social, contrasenia, rol) 
                VALUES (:nombre, :apellidos, :email, :telefono, :ruc, :razon_social, :contrasenia, 'EMPRESA_FACTURA')";
        
        $stmt = $this->conn->prepare($query);
        
        $datos['contrasenia'] = password_hash($datos['contrasenia'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellidos', $datos['apellidos']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':ruc', $datos['ruc']);
        $stmt->bindParam(':razon_social', $datos['razon_social']);
        $stmt->bindParam(':contrasenia', $datos['contrasenia']);
        
        if ($stmt->execute()) {
            $cliente_id = $this->conn->lastInsertId();
            $this->crearCarrito($cliente_id);
            return $cliente_id;
        }
        return false;
    }

    // Actualizar datos personales y de empresa

    public function actualizarInformacion($id_cliente, $datos) {
        $query = "UPDATE cliente SET 
                  nombre = :nombre, 
                  apellidos = :apellidos, 
                  telefono = :telefono,
                  ruc = :ruc,
                  razon_social = :razon_social
                  WHERE id_cliente = :id_cliente";
        
        $stmt = $this->conn->prepare($query);
        $datos['id_cliente'] = $id_cliente;
        return $stmt->execute($datos);
    }

    // Obtener datos de un solo cliente
    public function obtenerPorId($id_cliente) {
        $query = "SELECT * FROM cliente WHERE id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- GESTIÓN DE DIRECCIONES ---

    public function obtenerDistritos() {
        $query = "SELECT * FROM distrito ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarDireccion($id_cliente, $datos) {
        $query = "INSERT INTO direccion (id_cliente, alias, calle, numero, referencia, id_distrito) 
                  VALUES (:id_cliente, :alias, :calle, :numero, :referencia, :id_distrito)";
        $stmt = $this->conn->prepare($query);
        $datos['id_cliente'] = $id_cliente;
        return $stmt->execute($datos);
    }

    public function eliminarDireccion($id_direccion, $id_cliente) {
        // Validamos id_cliente para que nadie borre direcciones de otro
        $query = "DELETE FROM direccion WHERE id_direccion = :id_direccion AND id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_direccion', $id_direccion);
        $stmt->bindParam(':id_cliente', $id_cliente);
        return $stmt->execute();
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

    // Actualizar cliente desde el panel de Admin
    public function actualizarPorAdmin($id, $datos) {
        $query = "UPDATE cliente SET 
                nombre = :nombre, 
                apellidos = :apellidos, 
                email = :email, 
                telefono = :telefono, 
                ruc = :ruc, 
                razon_social = :razon_social,
                rol = :rol
                WHERE id_cliente = :id";
        
        $stmt = $this->conn->prepare($query);
        $datos['id'] = $id;
        
        try {
            return $stmt->execute($datos);
        } catch (PDOException $e) {
            // Si el email ya existe, dará error
            return false;
        }
    }

    // Eliminar cliente (La BD borrará en cascada pedidos y carritos gracias a las FK)
    public function eliminar($id) {
        $query = "DELETE FROM cliente WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function crearPorAdmin($datos) {
        $query = "INSERT INTO cliente (nombre, apellidos, email, telefono, ruc, razon_social, rol, contrasenia) 
                  VALUES (:nombre, :apellidos, :email, :telefono, :ruc, :razon_social, :rol, :pass)";
        
        // Contraseña por defecto: 123456
        $passHash = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare($query);
        $res = $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':apellidos' => $datos['apellidos'],
            ':email' => $datos['email'],
            ':telefono' => $datos['telefono'],
            ':ruc' => $datos['ruc'],
            ':razon_social' => $datos['razon_social'],
            ':rol' => $datos['rol'],
            ':pass' => $passHash
        ]);

        if ($res) {
            $id = $this->conn->lastInsertId();
            $this->crearCarrito($id); // Crear carrito automáticamente
            return $id;
        }
        return false;
    }
    
    public function cambiarContrasenia($id_cliente, $passActual, $passNueva) {
        // 1. Obtener la contraseña actual de la BD (hash)
        $stmt = $this->conn->prepare("SELECT contrasenia FROM cliente WHERE id_cliente = :id");
        $stmt->execute([':id' => $id_cliente]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$res) return false;

        // 2. Verificar que la contraseña actual ingresada coincida con el hash
        if (password_verify($passActual, $res['contrasenia'])) {
            // 3. Encriptar la nueva contraseña
            $nuevoHash = password_hash($passNueva, PASSWORD_DEFAULT);
            
            // 4. Actualizar en BD
            $sql = "UPDATE cliente SET contrasenia = :pass WHERE id_cliente = :id";
            $update = $this->conn->prepare($sql);
            return $update->execute([':pass' => $nuevoHash, ':id' => $id_cliente]);
        }

        return false; // Contraseña actual incorrecta
    }
}
?>