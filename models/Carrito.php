<?php
require_once '../app/core/Database.php';

class Carrito {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function obtenerCarrito($id_cliente) {
        $query = "SELECT c.id_carrito FROM carrito c WHERE c.id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosCarrito($id_cliente) {
        $query = "SELECT cp.*, p.nombre, p.precio_b2c, p.unidades_base_b2b, p.soles_base_b2b, c.nombre as categoria_nombre
                  FROM carrito_producto cp
                  JOIN carrito car ON cp.id_carrito = car.id_carrito
                  JOIN producto p ON cp.id_producto = p.id_producto
                  JOIN categoria c ON p.id_categoria = c.id_categoria
                  WHERE car.id_cliente = :id_cliente";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarProducto($id_cliente, $id_producto, $cantidad = null, $monto_solicitado = null) {
        $carrito = $this->obtenerCarrito($id_cliente);
        $id_carrito = $carrito['id_carrito'];

        // Verificar si el producto ya está en el carrito
        $query = "SELECT * FROM carrito_producto WHERE id_carrito = :id_carrito AND id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Actualizar cantidad existente
            $query = "UPDATE carrito_producto SET 
                      cantidad = COALESCE(:cantidad, cantidad),
                      monto_solicitado_entero = COALESCE(:monto_solicitado, monto_solicitado_entero)
                      WHERE id_carrito = :id_carrito AND id_producto = :id_producto";
        } else {
            // Insertar nuevo producto
            $query = "INSERT INTO carrito_producto (id_carrito, id_producto, cantidad, monto_solicitado_entero) 
                      VALUES (:id_carrito, :id_producto, :cantidad, :monto_solicitado)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':monto_solicitado', $monto_solicitado);
        
        return $stmt->execute();
    }

    public function eliminarProducto($id_cliente, $id_producto) {
        $carrito = $this->obtenerCarrito($id_cliente);
        $id_carrito = $carrito['id_carrito'];

        $query = "DELETE FROM carrito_producto WHERE id_carrito = :id_carrito AND id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        
        return $stmt->execute();
    }

    public function vaciarCarrito($id_cliente) {
        $carrito = $this->obtenerCarrito($id_cliente);
        $id_carrito = $carrito['id_carrito'];

        $query = "DELETE FROM carrito_producto WHERE id_carrito = :id_carrito";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_carrito', $id_carrito);
        
        return $stmt->execute();
    }

    public function calcularSubtotal($id_cliente, $rol_cliente) {
        $productos = $this->obtenerProductosCarrito($id_cliente);
        $subtotal = 0;

        foreach ($productos as $producto) {
            if ($rol_cliente == 'CLIENTE_ESTANDAR') {
                // B2C: usar precio_b2c
                $subtotal += $producto['cantidad'] * $producto['precio_b2c'];
            } else {
                // B2B: calcular según regla o usar cantidad directa
                if ($producto['monto_solicitado_entero']) {
                    $subtotal += $producto['monto_solicitado_entero'];
                } else {
                    // Para empanadas u otros por unidad en B2B
                    $precio_unitario = $producto['soles_base_b2b'] / $producto['unidades_base_b2b'];
                    $subtotal += $producto['cantidad'] * $precio_unitario;
                }
            }
        }

        return $subtotal;
    }
}
?>