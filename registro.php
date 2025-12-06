<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $query = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $query->bind_param("sss", $name, $email, $pass);

    if ($query->execute()) {
        header("Location: login.php?msg=registrado");
        exit;
    } else {
        $error = "Error al registrar.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta | Panadería Barrios</title>
    <link rel="stylesheet" href="public/estilos.css">

    <style>
        body.fondo-login {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('img/registro.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 15px 40px rgba(0,0,0,0.45);
            text-align: center;
        }

        .form-container h2 {
            font-size: 1.9rem;
            margin-bottom: 25px;
            text-shadow: 0 0 15px rgba(0,0,0,0.4);
        }

        .form-container input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 18px;
            border: none;
            outline: none;
            border-radius: 10px;
            font-size: 1rem;
            background: rgba(255,255,255,0.55);
            color: #222;
            transition: background .2s ease;
        }

        .form-container input:focus {
            background: rgba(255,255,255,0.8);
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: rgba(255, 200, 70, 0.6);
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            font-weight: bold;
            backdrop-filter: blur(10px);
            transition: .25s ease-in-out;
        }

        button:hover {
            background: rgba(255, 200, 70, 0.9);
            transform: translateY(-3px);
        }

        .error {
            background: rgba(255, 80, 80, 0.4);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            backdrop-filter: blur(10px);
        }

        a {
            color: #ffd670;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="fondo-login">

<div class="form-container">
    <h2>Crear Cuenta</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button>Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
</div>

</body>
</html>
