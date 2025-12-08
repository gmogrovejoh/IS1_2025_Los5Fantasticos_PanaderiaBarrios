<?php

class Pedido {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function crear($datos_pedido, $productos_carrito) {
        try {
            $this->conn->beginTransaction();

            // 1. INSERTAR PEDIDO (Cabecera)
            $query = "INSERT INTO pedido (id_cliente, id_sede, id_direccion_entrega, tipo_entrega, 
                      fecha_entrega, ventana_entrega, subtotal_productos, costo_envio, 
                      costo_total, estado, tipo_comprobante) 
                      VALUES (:id_cliente, :id_sede, :id_direccion_entrega, :tipo_entrega, 
                      :fecha_entrega, :ventana_entrega, :subtotal_productos, :costo_envio, 
                      :costo_total, 'PENDIENTE_PAGO', :tipo_comprobante)";

            $stmt = $this->conn->prepare($query);
            
            // Verificamos que los datos no sean null si la BD no lo permite
            // (Los datos vienen validados del controlador, pero aseguramos bind)
            $stmt->execute([
                ':id_cliente' => $datos_pedido['id_cliente'],
                ':id_sede' => $datos_pedido['id_sede'],
                ':id_direccion_entrega' => $datos_pedido['id_direccion_entrega'], // Puede ser NULL
                ':tipo_entrega' => $datos_pedido['tipo_entrega'],
                ':fecha_entrega' => $datos_pedido['fecha_entrega'],
                ':ventana_entrega' => $datos_pedido['ventana_entrega'],
                ':subtotal_productos' => $datos_pedido['subtotal_productos'],
                ':costo_envio' => $datos_pedido['costo_envio'],
                ':costo_total' => $datos_pedido['costo_total'],
                ':tipo_comprobante' => $datos_pedido['tipo_comprobante']
            ]);
            
            $id_pedido = $this->conn->lastInsertId();

            // 2. INSERTAR PRODUCTOS (Detalle)
            $query_producto = "INSERT INTO pedido_producto (id_pedido, id_producto, cantidad, 
                               precio_unitario_congelado, monto_solicitado_entero, subtotal) 
                               VALUES (:id_pedido, :id_producto, :cantidad, 
                               :precio_unitario, NULL, :subtotal)"; // Monto siempre NULL ahora
            
            $stmt_producto = $this->conn->prepare($query_producto);

            foreach ($productos_carrito as $producto) {
                // Calcular precio unitario real (congelado para la historia)
                if ($producto['unidades_base_b2b'] > 0 && $producto['soles_base_b2b'] > 0) {
                    $precio_unitario = $producto['soles_base_b2b'] / $producto['unidades_base_b2b'];
                } else {
                    $precio_unitario = $producto['precio_b2c'];
                }

                $cantidad = $producto['cantidad'];
                $subtotal_linea = $cantidad * $precio_unitario;

                $stmt_producto->execute([
                    ':id_pedido' => $id_pedido,
                    ':id_producto' => $producto['id_producto'],
                    ':cantidad' => $cantidad,
                    ':precio_unitario' => $precio_unitario,
                    ':subtotal' => $subtotal_linea
                ]);
            }

            $this->conn->commit();
            return $id_pedido;

        } catch (Exception $e) {
            $this->conn->rollback();
            // IMPORTANTE: Esto imprimirá el error exacto en pantalla si falla
            // En producción deberías guardarlo en un log, pero para arreglarlo ahora:
            die("Error SQL al crear pedido: " . $e->getMessage()); 
            return false;
        }
    }

    public function obtenerPorCliente($id_cliente) {
        $query = "SELECT p.*, s.nombre as sede_nombre 
                  FROM pedido p 
                  JOIN sede s ON p.id_sede = s.id_sede 
                  WHERE p.id_cliente = :id_cliente 
                  ORDER BY p.fecha_registro DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // Obtener cabecera de un pedido específico
    public function obtenerPorId($id_pedido) {
        $query = "SELECT p.*, s.nombre as sede_nombre, d.calle, d.numero, d.referencia, dist.nombre as distrito_nombre
                  FROM pedido p 
                  JOIN sede s ON p.id_sede = s.id_sede
                  LEFT JOIN direccion d ON p.id_direccion_entrega = d.id_direccion
                  LEFT JOIN distrito dist ON d.id_distrito = dist.id_distrito
                  WHERE p.id_pedido = :id_pedido";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pedido', $id_pedido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener los productos de ese pedido
    public function obtenerDetallesPedido($id_pedido) {
        $query = "SELECT pp.*, p.nombre, p.foto 
                  FROM pedido_producto pp 
                  JOIN producto p ON pp.id_producto = p.id_producto 
                  WHERE pp.id_pedido = :id_pedido";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pedido', $id_pedido);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularCostoEnvio($id_direccion, $subtotal) {
        if (!$id_direccion) return 0; // Si no hay dirección, 0

        // 1. Obtenemos la regla de envío según el distrito de la dirección
        // Hacemos JOIN entre direccion -> distrito -> zona_disponible_envio
        $sql = "SELECT z.costo_envio, z.monto_minimo_gratis 
                FROM direccion d
                JOIN zona_disponible_envio z ON d.id_distrito = z.id_distrito
                WHERE d.id_direccion = :id_dir AND z.id_sede = 1 LIMIT 1"; 
                // Asumimos sede 1 por ahora, ajusta si tienes múltiples sedes activas

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_dir', $id_direccion);
        $stmt->execute();
        $zona = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$zona) {
            return 10.00; // Costo por defecto si no hay zona configurada
        }

        // 2. Aplicar lógica de envío gratis
        if ($zona['monto_minimo_gratis'] > 0 && $subtotal >= $zona['monto_minimo_gratis']) {
            return 0; // ¡Es gratis!
        }

        return $zona['costo_envio'];
    }

    public function obtenerDatosZona($id_direccion) {
        if (!$id_direccion) return null;

        $sql = "SELECT z.costo_envio, z.monto_minimo_gratis 
                FROM direccion d
                JOIN zona_disponible_envio z ON d.id_distrito = z.id_distrito
                WHERE d.id_direccion = :id_dir AND z.id_sede = 1 LIMIT 1"; 

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_dir', $id_direccion);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>