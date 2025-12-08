<?php
require_once '../app/core/Database.php';

class Pedido {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function crear($datos_pedido, $productos_carrito) {
        try {
            $this->conn->beginTransaction();

            // Insertar pedido
            $query = "INSERT INTO pedido (id_cliente, id_sede, id_direccion_entrega, tipo_entrega, 
                      fecha_entrega, ventana_entrega, subtotal_productos, costo_envio, 
                      costo_total, estado, tipo_comprobante) 
                      VALUES (:id_cliente, :id_sede, :id_direccion_entrega, :tipo_entrega, 
                      :fecha_entrega, :ventana_entrega, :subtotal_productos, :costo_envio, 
                      :costo_total, 'PENDIENTE_PAGO', :tipo_comprobante)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($datos_pedido);
            $id_pedido = $this->conn->lastInsertId();

            // Insertar productos del pedido
            foreach ($productos_carrito as $producto) {
                $precio_unitario = $this->calcularPrecioUnitario($producto, $datos_pedido['rol_cliente']);
                $cantidad = $producto['cantidad'] ?: $this->calcularCantidadDesdeMonto($producto);
                $subtotal = $cantidad * $precio_unitario;

                $query_producto = "INSERT INTO pedido_producto (id_pedido, id_producto, cantidad, 
                                   precio_unitario_congelado, monto_solicitado_entero, subtotal) 
                                   VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario, 
                                   :monto_solicitado, :subtotal)";

                $stmt_producto = $this->conn->prepare($query_producto);
                $stmt_producto->execute([
                    'id_pedido' => $id_pedido,
                    'id_producto' => $producto['id_producto'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio_unitario,
                    'monto_solicitado' => $producto['monto_solicitado_entero'],
                    'subtotal' => $subtotal
                ]);
            }

            $this->conn->commit();
            return $id_pedido;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function obtenerVentanasDisponibles($fecha_entrega) {
        $ventanas = [];
        $hora_actual = date('H:i');
        $fecha_actual = date('Y-m-d');

        // Si es para mañana, verificar hora de corte
        if ($fecha_entrega == date('Y-m-d', strtotime('+1 day'))) {
            if ($hora_actual <= HORA_CORTE_MANANA) {
                $ventanas[] = 'MAÑANA';
            }
            $ventanas[] = 'TARDE';
        } else if ($fecha_entrega == $fecha_actual) {
            // Si es para hoy, solo tarde si no pasó la hora de corte
            if ($hora_actual <= HORA_CORTE_TARDE) {
                $ventanas[] = 'TARDE';
            }
        } else {
            // Para fechas futuras, ambas ventanas disponibles
            $ventanas = ['MAÑANA', 'TARDE'];
        }

        return $ventanas;
    }

    public function obtenerZonaEnvio($id_distrito) {
        $query = "SELECT * FROM zona_disponible_envio WHERE id_distrito = :id_distrito";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_distrito', $id_distrito);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosProduccion($fecha_entrega, $ventana_entrega) {
        $query = "SELECT p.id_pedido, pp.id_producto, pp.cantidad, pr.nombre as producto_nombre
                  FROM pedido p
                  JOIN pedido_producto pp ON p.id_pedido = pp.id_pedido
                  JOIN producto pr ON pp.id_producto = pr.id_producto
                  WHERE p.fecha_entrega = :fecha_entrega 
                  AND p.ventana_entrega = :ventana_entrega
                  AND p.estado NOT IN ('CANCELADO')
                  ORDER BY pr.nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha_entrega', $fecha_entrega);
        $stmt->bindParam(':ventana_entrega', $ventana_entrega);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularProduccionTotal($fecha_entrega, $ventana_entrega) {
        $pedidos = $this->obtenerPedidosProduccion($fecha_entrega, $ventana_entrega);
        $produccion = [];

        foreach ($pedidos as $item) {
            // Verificar si es un pack
            $query = "SELECT pp.id_componente, pp.cantidad as cantidad_componente, pr.nombre
                      FROM pack_producto pp
                      JOIN producto pr ON pp.id_componente = pr.id_producto
                      WHERE pp.id_pack = :id_pack";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pack', $item['id_producto']);
            $stmt->execute();
            $componentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($componentes) > 0) {
                // Es un pack, descomponer
                foreach ($componentes as $componente) {
                    $producto_nombre = $componente['nombre'];
                    $cantidad_total = $item['cantidad'] * $componente['cantidad_componente'];
                    
                    if (!isset($produccion[$producto_nombre])) {
                        $produccion[$producto_nombre] = 0;
                    }
                    $produccion[$producto_nombre] += $cantidad_total;
                }
            } else {
                // No es un pack, sumar directamente
                if (!isset($produccion[$item['producto_nombre']])) {
                    $produccion[$item['producto_nombre']] = 0;
                }
                $produccion[$item['producto_nombre']] += $item['cantidad'];
            }
        }

        return $produccion;
    }

    private function calcularPrecioUnitario($producto, $rol_cliente) {
        if ($rol_cliente == 'CLIENTE_ESTANDAR') {
            return $producto['precio_b2c'];
        } else {
            return $producto['soles_base_b2b'] / $producto['unidades_base_b2b'];
        }
    }

    private function calcularCantidadDesdeMonto($producto) {
        if ($producto['monto_solicitado_entero']) {
            return ($producto['monto_solicitado_entero'] / $producto['soles_base_b2b']) * $producto['unidades_base_b2b'];
        }
        return $producto['cantidad'];
    }
}
?>