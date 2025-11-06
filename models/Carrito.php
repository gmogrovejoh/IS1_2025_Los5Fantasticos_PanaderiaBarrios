<?php
class Carrito {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerOCrearCarrito($id_cliente) {
        // Buscar carrito existente
        $sql = "SELECT id_carrito FROM carrito WHERE id_cliente = :id_cliente";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        
        $carrito = $stmt->fetch();
        
        if (!$carrito) {
            // Crear nuevo carrito
            $sql = "INSERT INTO carrito (id_cliente) VALUES (:id_cliente)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->execute();
            
            return $this->db->lastInsertId();
        }
        
        return $carrito['id_carrito'];
    }
    
    public function agregarProducto($id_carrito, $id_producto, $cantidad = 1) {
        // Verificar si el producto ya está en el carrito
        $sql = "SELECT cantidad FROM carrito_producto 
                WHERE id_carrito = :id_carrito AND id_producto = :id_producto";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        
        $producto_existente = $stmt->fetch();
        
        if ($producto_existente) {
            // Actualizar cantidad
            $nueva_cantidad = $producto_existente['cantidad'] + $cantidad;
            $sql = "UPDATE carrito_producto 
                    SET cantidad = :cantidad 
                    WHERE id_carrito = :id_carrito AND id_producto = :id_producto";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cantidad', $nueva_cantidad);
            $stmt->bindParam(':id_carrito', $id_carrito);
            $stmt->bindParam(':id_producto', $id_producto);
        } else {
            // Agregar nuevo producto
            $sql = "INSERT INTO carrito_producto (id_carrito, id_producto, cantidad) 
                    VALUES (:id_carrito, :id_producto, :cantidad)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_carrito', $id_carrito);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->bindParam(':cantidad', $cantidad);
        }
        
        return $stmt->execute();
    }
    
    public function obtenerProductos($id_carrito) {
        $sql = "SELECT cp.*, p.nombre, p.precio, p.foto, p.puntos,
                       (cp.cantidad * p.precio) as subtotal
                FROM carrito_producto cp
                INNER JOIN producto p ON cp.id_producto = p.id_producto
                WHERE cp.id_carrito = :id_carrito";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function actualizarCantidad($id_carrito, $id_producto, $cantidad) {
        if ($cantidad <= 0) {
            return $this->eliminarProducto($id_carrito, $id_producto);
        }
        
        $sql = "UPDATE carrito_producto 
                SET cantidad = :cantidad 
                WHERE id_carrito = :id_carrito AND id_producto = :id_producto";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        
        return $stmt->execute();
    }
    
    public function eliminarProducto($id_carrito, $id_producto) {
        $sql = "DELETE FROM carrito_producto 
                WHERE id_carrito = :id_carrito AND id_producto = :id_producto";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        
        return $stmt->execute();
    }
    
    public function vaciarCarrito($id_carrito) {
        $sql = "DELETE FROM carrito_producto WHERE id_carrito = :id_carrito";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrito', $id_carrito);
        
        return $stmt->execute();
    }
    
    public function obtenerTotal($id_carrito) {
        $sql = "SELECT SUM(cp.cantidad * p.precio) as total
                FROM carrito_producto cp
                INNER JOIN producto p ON cp.id_producto = p.id_producto
                WHERE cp.id_carrito = :id_carrito";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->execute();
        
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }
}
?>