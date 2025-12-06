<?php
// nosotros.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nosotros | Panadería Barrios</title>
    <link rel="stylesheet" href="public/estilos.css">

    <style>
        body.nosotros-bg {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('img/123.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .nosotros-wrapper {
            width: 90%;
            max-width: 900px;

            background: rgba(255, 255, 255, 0.15);
            padding: 40px;
            border-radius: 25px;
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 15px 45px rgba(0,0,0,0.45);

            text-align: center;
        }

        .nosotros-wrapper h1 {
            font-size: 2.4rem;
            margin-bottom: 20px;
            text-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        .nosotros-wrapper p {
            font-size: 1.15rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .nosotros-img {
            width: 100%;
            max-width: 750px;
            border-radius: 18px;
            margin: 25px auto;
            box-shadow: 0 10px 35px rgba(0,0,0,0.45);
        }

        .valores-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 35px;
        }

        .valor-card {
            padding: 20px;
            background: rgba(255,255,255,0.18);
            border-radius: 18px;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
        }

        .valor-card h3 {
            margin-bottom: 10px;
        }

    </style>
</head>
<body class="nosotros-bg">

<div class="nosotros-wrapper">
    <h1>Sobre Nosotros</h1>

    <p>
        En <strong>Panadería Barrios</strong> llevamos más de 20 años dedicándonos al arte de la panadería artesanal,
        combinando recetas tradicionales con técnicas modernas para garantizar productos frescos, cálidos y llenos de sabor.
    </p>

    <p>
        Nuestro compromiso es ofrecer a cada cliente una experiencia única. Desde el aroma del pan recién horneado
        hasta la calidad de nuestros insumos, trabajamos con pasión para ser la panadería favorita de tu barrio.
    </p>

    <h2>Nuestros Valores</h2>
    
    <div class="valores-box">
        <div class="valor-card">
            <h3>🥐 Pasión por lo Artesanal</h3>
            <p>Elaboramos cada producto de forma tradicional, con dedicación y respeto por las recetas de siempre.</p>
        </div>

        <div class="valor-card">
            <h3>🌾 Ingredientes de Calidad</h3>
            <p>Seleccionamos insumos frescos y de primera para garantizar el mejor sabor en cada bocado.</p>
        </div>

        <div class="valor-card">
            <h3>🤝 Compromiso con el Cliente</h3>
            <p>Buscamos crear lazos con nuestra comunidad, ofreciendo atención cercana y productos para todos.</p>
        </div>
    </div>

</div>

</body>
</html>
