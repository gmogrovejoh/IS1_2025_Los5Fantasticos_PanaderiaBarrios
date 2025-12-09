<?php

class Producto {
    private $conn;
    private $table = 'producto';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function obtenerCategorias() {
        $query = "SELECT * FROM categoria";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $datos) {
        $query = "UPDATE producto SET 
                  nombre = :nombre, descripcion = :descripcion, precio_b2c = :precio_b2c,
                  unidades_base_b2b = :unidades_base_b2b, soles_base_b2b = :soles_base_b2b,
                  unidad_minima_b2b = :unidad_minima_b2b, disponible_b2b = :disponible_b2b, 
                  id_categoria = :id_categoria, foto = :foto
                  WHERE id_producto = :id";
        
        // Truco: Si foto es null, no la actualizamos en SQL (o manejamos lógica arriba)
        // Pero para simplificar, el controlador ya decidió qué nombre de foto enviar
        
        $stmt = $this->conn->prepare($query);
        $datos['id'] = $id;
        return $stmt->execute($datos);
    }

    public function eliminar($id) {
        $query = "DELETE FROM producto WHERE id_producto = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function obtenerCatalogoB2C() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  JOIN categoria c ON p.id_categoria = c.id_categoria 
                  WHERE p.disponible_b2c = 1 
                  AND c.nombre IN ('Packs y Ofertas', 'Pastelería y Repostería', 'Especiales de Temporada')
                  ORDER BY c.nombre, p.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCatalogoB2B() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  JOIN categoria c ON p.id_categoria = c.id_categoria 
                  WHERE p.disponible_b2b = 1 
                  ORDER BY c.nombre, p.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  JOIN categoria c ON p.id_categoria = c.id_categoria 
                  WHERE p.id_producto = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerComponentesPack($id_pack) {
        $query = "SELECT pp.cantidad, p.nombre, p.precio_b2c 
                  FROM pack_producto pp 
                  JOIN producto p ON pp.id_componente = p.id_producto 
                  WHERE pp.id_pack = :id_pack";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pack', $id_pack);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularPrecioSeparado($id_pack) {
        $componentes = $this->obtenerComponentesPack($id_pack);
        $total = 0;
        foreach ($componentes as $componente) {
            $total += $componente['cantidad'] * $componente['precio_b2c'];
        }
        return $total;
    }

    public function esPack($id_producto) {
        $query = "SELECT COUNT(*) as count FROM pack_producto WHERE id_pack = :id_pack";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pack', $id_producto);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function obtenerTodos() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  JOIN categoria c ON p.id_categoria = c.id_categoria 
                  ORDER BY c.nombre, p.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, foto, precio_b2c, unidades_base_b2b, soles_base_b2b, 
                   unidad_minima_b2b, disponible_b2c, disponible_b2b, id_categoria) 
                  VALUES (:nombre, :descripcion, :foto, :precio_b2c, :unidades_base_b2b, :soles_base_b2b, 
                          :unidad_minima_b2b, :disponible_b2c, :disponible_b2b, :id_categoria)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($datos);
    }

}
?>