<?php
require "conexion.php";
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Panadería Barrios</title>
    <link rel="stylesheet" href="public/estilos.css">
</head>
<body>

<?php include "nav.php"; ?>

<section class="dashboard">
    <div class="dash-container">
        <h1 class="dash-title">Panel Administrativo</h1>

        <div class="cards">

            <a class="card" href="crear_producto.php">
                <span class="icon">➕</span>
                <span>Crear Producto</span>
            </a>

            <a class="card" href="productos.php">
                <span class="icon">📦</span>
                <span>Ver Productos</span>
            </a>

            <!-- NUEVO CARD PARA ELIMINACIÓN -->
            <a class="card danger" href="eliminar_producto.php">
                <span class="icon">🗑️</span>
                <span>Eliminar Producto</span>
            </a>

            <a class="card red" href="logout.php">
                <span class="icon">🔒</span>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </div>
</section>

</body>
</html>
