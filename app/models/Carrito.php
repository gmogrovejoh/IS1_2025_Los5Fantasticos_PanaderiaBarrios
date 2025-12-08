<?php

class Carrito {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Función auxiliar para obtener o crear el ID del carrito
    private function obtenerIdCarrito($id_cliente) {
        $query = "SELECT id_carrito FROM carrito WHERE id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return $res['id_carrito'];
        } else {
            // Si no existe, lo creamos
            $insert = "INSERT INTO carrito (id_cliente) VALUES (:id_cliente)";
            $stmt = $this->conn->prepare($insert);
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->execute();
            return $this->conn->lastInsertId();
        }
    }

    // --- ESTA ES LA FUNCIÓN QUE TE FALTABA ---
    public function obtenerProductos($id_cliente) {
        $sql = "SELECT cp.*, 
                       p.nombre, 
                       p.foto, 
                       p.precio_b2c, 
                       p.unidades_base_b2b, 
                       p.soles_base_b2b, 
                       p.unidad_minima_b2b,
                       c.nombre as categoria_nombre
                FROM carrito_producto cp
                JOIN carrito car ON cp.id_carrito = car.id_carrito
                JOIN producto p ON cp.id_producto = p.id_producto
                JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE car.id_cliente = :id_cliente";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar producto (Simplificado solo para Cantidad)
    public function agregarProducto($id_cliente, $id_producto, $cantidad) {
        $id_carrito = $this->obtenerIdCarrito($id_cliente);

        // Verificar si ya existe
        $check = "SELECT * FROM carrito_producto WHERE id_carrito = :idc AND id_producto = :idp";
        $stmt = $this->conn->prepare($check);
        $stmt->execute([':idc' => $id_carrito, ':idp' => $id_producto]);

        if ($stmt->rowCount() > 0) {
            // ACTUALIZAR cantidad
            $sql = "UPDATE carrito_producto SET cantidad = :cant, monto_solicitado_entero = NULL 
                    WHERE id_carrito = :idc AND id_producto = :idp";
        } else {
            // INSERTAR nuevo
            $sql = "INSERT INTO carrito_producto (id_carrito, id_producto, cantidad, monto_solicitado_entero) 
                    VALUES (:idc, :idp, :cant, NULL)";
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':idc' => $id_carrito,
            ':idp' => $id_producto,
            ':cant' => $cantidad
        ]);
    }

    public function eliminarProducto($id_cliente, $id_producto) {
        $id_carrito = $this->obtenerIdCarrito($id_cliente);
        
        $sql = "DELETE FROM carrito_producto WHERE id_carrito = :idc AND id_producto = :idp";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':idc' => $id_carrito,
            ':idp' => $id_producto
        ]);
    }

    public function vaciarCarrito($id_cliente) {
        $id_carrito = $this->obtenerIdCarrito($id_cliente);
        
        $sql = "DELETE FROM carrito_producto WHERE id_carrito = :idc";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':idc' => $id_carrito]);
    }

    public function calcularTotal($id_cliente) {
        $productos = $this->obtenerProductos($id_cliente);
        $total = 0;

        foreach ($productos as $p) {
            $cantidad = $p['cantidad'];
            
            // Calcular precio unitario
            if ($p['unidades_base_b2b'] > 0 && $p['soles_base_b2b'] > 0) {
                // Precio B2B (ej: 0.1666...)
                $precio_unitario = $p['soles_base_b2b'] / $p['unidades_base_b2b'];
            } else {
                // Precio normal
                $precio_unitario = $p['precio_b2c'];
            }

            $total += ($cantidad * $precio_unitario);
        }

        return $total;
    }
}
?>