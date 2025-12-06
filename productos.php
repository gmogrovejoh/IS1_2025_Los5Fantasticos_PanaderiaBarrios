<?php
require "conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos | Panadería Barrios</title>
    <link rel="stylesheet" href="public/estilos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

<?php include "nav.php"; ?>

<div class="contenedor">
    <h1 class="titulo-seccion"><i class="bi bi-bag-heart"></i> Nuestros Productos</h1>

    <div class="grid-productos">
        <?php
        $res = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
        if ($res->num_rows > 0) {
            while($p = $res->fetch_assoc()){
        ?>
        <div class="card-producto">
            <img src="img/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
            
            <h2><?= htmlspecialchars($p['nombre']) ?></h2>
            <p class="precio">S/ <?= number_format($p['precio'], 2) ?></p>

            <button class="btn-agregar">
                <i class="bi bi-cart-plus"></i> Añadir al carrito
            </button>
        </div>
        <?php
            }
        } else {
            echo "<p class='no-prod'>No hay productos registrados.</p>";
        }
        ?>
        
    </div>
</div>

</body>
</html>
