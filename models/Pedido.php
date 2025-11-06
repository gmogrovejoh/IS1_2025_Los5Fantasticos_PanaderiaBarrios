<?php
class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function crear($datos) {
        $sql = "INSERT INTO pedido (tipo_retiro, costo_envio, costo_producto, costo_total, 
                fecha_entrega, hora_entrega, id_cliente, id_sede, id_ubicacion) 
                VALUES (:tipo_retiro, :costo_envio, :costo_producto, :costo_total, 
                :fecha_entrega, :hora_entrega, :id_cliente, :id_sede, :id_ubicacion)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tipo_retiro', $datos['tipo_retiro']);
        $stmt->bindParam(':costo_envio', $datos['costo_envio']);
        $stmt->bindParam(':costo_producto', $datos['costo_producto']);
        $stmt->bindParam(':costo_total', $datos['costo_total']);
        $stmt->bindParam(':fecha_entrega', $datos['fecha_entrega']);
        $stmt->bindParam(':hora_entrega', $datos['hora_entrega']);
        $stmt->bindParam(':id_cliente', $datos['id_cliente']);
        $stmt->bindParam(':id_sede', $datos['id_sede']);
        $stmt->bindParam(':id_ubicacion', $datos['id_ubicacion']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    public function agregarProductos($id_pedido, $productos) {
        foreach ($productos as $producto) {
            $sql = "INSERT INTO pedido_producto (id_pedido, id_producto, cantidad) 
                    VALUES (:id_pedido, :id_producto, :cantidad)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_pedido', $id_pedido);
            $stmt->bindParam(':id_producto', $producto['id_producto']);
            $stmt->bindParam(':cantidad', $producto['cantidad']);
            $stmt->execute();
        }
        
        return true;
    }
    
    public function obtenerPorCliente($id_cliente) {
        $sql = "SELECT p.*, s.nombre as sede_nombre 
                FROM pedido p
                INNER JOIN sede s ON p.id_sede = s.id_sede
                WHERE p.id_cliente = :id_cliente
                ORDER BY p.fecha_registro DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId($id_pedido) {
        $sql = "SELECT p.*, s.nombre as sede_nombre, c.nombre as cliente_nombre,c.apellidos as cliente_apellidos
                FROM pedido p
                INNER JOIN sede s ON p.id_sede = s.id_sede
                INNER JOIN cliente c ON p.id_cliente = c.id_cliente
                WHERE p.id_pedido = :id_pedido";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_pedido', $id_pedido);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function obtenerProductosPedido($id_pedido) {
        $sql = "SELECT pp.*, p.nombre, p.precio, p.foto,
                       (pp.cantidad * p.precio) as subtotal
                FROM pedido_producto pp
                INNER JOIN producto p ON pp.id_producto = p.id_producto
                WHERE pp.id_pedido = :id_pedido";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_pedido', $id_pedido);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function actualizarEstado($id_pedido, $estado) {
        $sql = "UPDATE pedido SET estado = :estado WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id_pedido', $id_pedido);
        
        return $stmt->execute();
    }
}
?>