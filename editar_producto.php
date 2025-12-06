<?php
require "conexion.php";

$id = $_GET["id"];
$consulta = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$producto = $consulta->get_result()->fetch_assoc();

if (!$producto) {
    die("Producto no encontrado");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];

    $update = $conexion->prepare("UPDATE productos SET nombre = ?, precio = ? WHERE id = ?");
    $update->bind_param("sdi", $nombre, $precio, $id);

    if ($update->execute()) {
        header("Location: productos.php?msg=editado");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="public/estilos.css">
</head>
<body>

<?php include "nav.php"; ?>

<div class="form-container">
    <h2>Editar Producto</h2>

    <form method="POST">
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>
        <button>Actualizar</button>
    </form>
</div>

</body>
</html>
