<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = $conexion->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["usuario_id"] = $user["id"];
            $_SESSION["usuario_nombre"] = $user["nombre"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El correo no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | Panadería Barrios</title>
    <link rel="stylesheet" href="public/estilos.css">
</head>
<body>

<?php include "nav.php"; ?>

<div class="form-container">
    <form action="" method="POST" class="form-card">

        <h2>Iniciar Sesión</h2>

        <?php if (!empty($error)): ?>
            <p class="error-msg"><?= $error ?></p>
        <?php endif; ?>

        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>

        <button type="submit" class="btn">Entrar</button>

        <p class="texto-sec">¿No tienes cuenta? 
           <a href="registro.php">Regístrate aquí</a>
        </p>
    </form>
</div>

</body>
</html>
