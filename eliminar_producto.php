<?php
// eliminar_producto.php
require "conexion.php";
session_start();

$mensaje = "";

// Manejo de eliminación (POST)
if (isset($_POST['eliminar_id'])) {
    $id = intval($_POST['eliminar_id']);

    $sql = "DELETE FROM productos WHERE id = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $mensaje = "Producto eliminado correctamente.";
        } else {
            $mensaje = "Error al eliminar el producto.";
        }
        $stmt->close();
    } else {
        $mensaje = "Error en la consulta de eliminación.";
    }
}

// Búsqueda (GET)
$resultados = null;
$buscar_query = "";
if (isset($_GET['buscar'])) {
    $buscar_raw = trim($_GET['buscar']);
    $buscar_query = $buscar_raw; // para mantener el valor en el input
    $buscar = "%" . $buscar_raw . "%";

    $sql = "SELECT id, nombre, precio FROM productos WHERE nombre LIKE ? ORDER BY nombre ASC";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("s", $buscar);
        $stmt->execute();

        // Intentamos usar get_result (requiere mysqlnd). Si no existe, hacemos bind_result.
        $result = null;
        if (method_exists($stmt, 'get_result')) {
            $result = $stmt->get_result();
        } else {
            // Fallback: recoger manualmente
            $meta = $stmt->result_metadata();
            $fields = [];
            $row = [];
            while ($field = $meta->fetch_field()) {
                $fields[] = &$row[$field->name];
            }
            call_user_func_array([$stmt, 'bind_result'], $fields);
            $rows = [];
            while ($stmt->fetch()) {
                $r = [];
                foreach ($row as $k => $v) $r[$k] = $v;
                $rows[] = $r;
            }
            $result = $rows; // array fallback
        }

        $resultados = $result;
        $stmt->close();
    } else {
        $mensaje = "Error en la consulta de búsqueda.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Producto | Panadería Barrios</title>
    <link rel="stylesheet" href="public/estilos.css">
    <style>
        .contenedor { width: 90%; max-width: 1000px; margin: 40px auto; }
        .buscador { display:flex; gap:10px; margin-bottom:20px; }
        .buscador input { flex:1; padding:10px; font-size:16px; border-radius:8px; border:none; }
        .buscador button { padding:10px 18px; border-radius:8px; border:none; cursor:pointer; background: rgba(255,200,70,0.8); color:#111; font-weight:bold; }
        table { width:100%; margin-top:20px; border-collapse:collapse; background: rgba(255,255,255,0.06); border-radius:8px; overflow:hidden; }
        table th, table td { padding:12px; border-bottom:1px solid rgba(255,255,255,0.06); color:#fff; text-align:left; }
        .btn-eliminar { background: rgba(255,80,80,0.35); padding:8px 14px; color:#fff; border-radius:8px; border:none; cursor:pointer; }
        .btn-eliminar:hover { background: rgba(255,60,60,0.75); transform: translateY(-2px); }
        .mensaje { background: rgba(255, 255, 255, 0.12); padding:10px; border-radius:8px; color:#fff; margin-bottom:15px; }
        .sin-resultados { color:#ffd670; }
        .small { font-size:0.9rem; color: rgba(255,255,255,0.8); }
    </style>
</head>
<body class="fondo-login">

<?php include "nav.php"; ?>

<div class="contenedor">
    <h1>Eliminar Producto</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <!-- BUSCADOR -->
    <form class="buscador" method="GET" action="eliminar_producto.php">
        <input type="text" name="buscar" placeholder="Buscar producto por nombre..." value="<?php echo htmlspecialchars($buscar_query); ?>" required>
        <button type="submit">Buscar</button>
    </form>

    <!-- RESULTADOS -->
    <?php if (isset($_GET['buscar'])): ?>
        <h2 class="small">Resultados de búsqueda</h2>

        <?php
        // Si usamos get_result (objeto mysqli_result)
        if ($resultados instanceof mysqli_result):
            if ($resultados->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $resultados->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td>S/ <?php echo number_format($row['precio'], 2); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');" style="margin:0;">
                                    <input type="hidden" name="eliminar_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button class="btn-eliminar" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="sin-resultados">No se encontraron productos con ese nombre.</p>
            <?php endif;

        // Si usamos fallback (array)
        elseif (is_array($resultados)):
            if (count($resultados) > 0): ?>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Acción</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($resultados as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td>S/ <?php echo number_format($row['precio'], 2); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');" style="margin:0;">
                                    <input type="hidden" name="eliminar_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button class="btn-eliminar" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="sin-resultados">No se encontraron productos con ese nombre.</p>
            <?php endif;

        else: ?>
            <p class="small">Realiza una búsqueda para ver resultados.</p>
        <?php endif; ?>
    <?php endif; ?>

</div>

</body>
</html>
