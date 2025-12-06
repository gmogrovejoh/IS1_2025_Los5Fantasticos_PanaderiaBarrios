<?php include "../conexion.php"; ?>

<!DOCTYPE html>
<html>
<head>
<title>Carrito</title>
<link rel="stylesheet" href="../estilos.css">
</head>
<body>

<h2>Carrito de Compras</h2>

<?php
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    echo "<p>El carrito está vacío.</p>";
    exit;
}

$total = 0;

foreach ($_SESSION['carrito'] as $id => $cant) {
    $res = $conexion->query("SELECT * FROM productos WHERE id=$id");
    $prod = $res->fetch_assoc();
?>
<div class="item-carrito">
    <img src="../img/<?= $prod['imagen'] ?>" width="70">
    <b><?= $prod['nombre'] ?></b> x <?= $cant ?>  
    <span>$<?= $prod['precio'] * $cant ?></span>
    <a href="eliminar.php?id=<?= $id ?>">Eliminar</a>
</div>
<?php
$total += $prod['precio'] * $cant;
}
?>

<h3>Total: $<?= $total ?></h3>
<a href="../index.php" class="btn">Seguir comprando</a>

</body>
</html>
