<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];

    $query = $conexion->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)");
    $query->bind_param("sd", $nombre, $precio);

    if ($query->execute()) {
        header("Location: productos.php?msg=creado");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="public/estilos.css">
</head>
<body>

<?php include "nav.php"; ?>

<section class="form-wrapper">
    <div class="form-glass">
        <h2>Crear Producto</h2>

        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="number" step="0.01" name="precio" placeholder="Precio" required>

            <button class="btn-glass">Guardar</button>
        </form>
    </div>
</section>

</body>
</html>
