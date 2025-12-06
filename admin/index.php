<?php
include "../conexion.php";
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 'admin') {
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Panel Admin</title>
<link rel="stylesheet" href="../estilos.css">
</head>
<body>

<h1>Panel de Administración</h1>
<a href="productos.php" class="btn">Administrar Productos</a>
<a href="../logout.php" class="btn-rojo">Salir</a>

</body>
</html>
