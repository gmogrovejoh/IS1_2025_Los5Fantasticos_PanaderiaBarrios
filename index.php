<?php
// Asegúrate de que el archivo 'conexion.php' maneje la conexión y establezca la variable $conexion
require "conexion.php";

if (isset($conexion) && $conexion->connect_error) {
    // Si la conexión falló, muestra un error y detén la ejecución para evitar problemas
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panadería Barrios | El Sabor de la Tradición</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="public/estilos.css"> 

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body>

<div id="loader">
    <div class="pan-loader">
        <div class="pan"></div>
        <div class="bread"></div>
    </div>
    <p>Horneando tu experiencia...</p>
</div>

<nav class="nav" role="navigation">

    <div class="nav-left">
        <span class="logo-text">Panadería Barrios</span>
    </div>

    <div class="nav-right">
        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="ofertas.php">Ofertas</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>

</nav>

<div class="menu-flotante" aria-label="Menú rápido de secciones">
    <a href="#inicio" title="Ir a Inicio">🏠</a>
    <a href="#productos" title="Ir a Productos">🥐</a>
    <a href="#ofertas" title="Ir a Ofertas">🔥</a>
    <a href="#contacto" title="Ir a Contacto">📞</a>
</div>

<main>

    <section class="hero" id="inicio" aria-label="Introducción: Panadería Barrios">
      <div class="hero-content" data-aos="fade-up">
        <h1>Elaboración Artesanal, Sabor Inigualable</h1>
        <p>Desde 1998, llevando la mejor tradición panadera a tu mesa.</p>
        <a href="#productos" class="btn">Ver Nuestros Productos</a> 
      </div>
    </section>
    
    <section class="secreto-cta" aria-label="Nuestro secreto">
        <div class="cta-content" data-aos="fade-up">
            <h2>🥖 Calidad y Frescura Garantizada 🍞</h2>
            <p>Usamos masa madre y los mejores ingredientes locales. ¡Pruébanos y siente la diferencia!</p>
        </div>
    </section>

    <section class="productos" id="productos" aria-label="Productos Destacados">
        <h2 data-aos="fade-up">Productos Destacados</h2>

        <div class="grid" data-aos="fade-up">

            <?php
            $res = $conexion->query("SELECT id, nombre, imagen, precio FROM productos LIMIT 6"); // Limitado a 6 para un mejor diseño
            if($res && $res->num_rows > 0){
                while($p = $res->fetch_assoc()) {
            ?>
            <div class="card" data-aos="zoom-in">
                <img src="img/<?= htmlspecialchars($p['imagen']) ?>" alt="Producto: <?= htmlspecialchars($p['nombre']) ?>"> 
                <h3><?= htmlspecialchars($p['nombre']) ?></h3> 
                <p class="precio">S/ <?= number_format($p['precio'],2) ?></p>
                <a href="carrito/agregar.php?id=<?= intval($p['id']) ?>" class="btn">Comprar</a>
            </div>
            <?php
                }
            } else {
                echo '<p class="no-productos">No se encontraron productos.</p>';
            }
            ?>

        </div>
    </section>

    <section class="ofertas " id="ofertas" aria-label="Ofertas del día">
        <h2 data-aos="fade-up">🎉 Imperdibles de Hoy</h2>

        <div class="swiper ofertasSwiper" data-aos="fade-up">
            <div class="swiper-wrapper">

                <?php
                $ofertas = $conexion->query("SELECT id, nombre, imagen, precio FROM productos ORDER BY precio ASC LIMIT 5");
                if($ofertas && $ofertas->num_rows > 0){
                    while ($o = $ofertas->fetch_assoc()) {
                ?>
                <div class="swiper-slide oferta-card">
                    <img src="img/<?= htmlspecialchars($o['imagen']) ?>" alt="Oferta: <?= htmlspecialchars($o['nombre']) ?>">
                    <h3><?= htmlspecialchars($o['nombre']) ?></h3> 
                    <p class="precio">S/ <?= number_format($o['precio'],2) ?></p>
                    <a href="carrito/agregar.php?id=<?= intval($o['id']) ?>" class="btn">Agregar</a>
                </div>
                <?php
                    }
                } else {
                    echo '<p class="no-ofertas">No hay ofertas disponibles en este momento.</p>';
                }
                ?>

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="nosotros" id="nosotros" aria-label="Sobre nosotros: Panadería familiar">
        <div class="nosotros-content" data-aos="fade-up">
            <div class="nosotros-texto">
                <h2>Nuestra Historia y Misión</h2>
                <p>
                    Panadería Barrios nació de la pasión por el buen pan. Utilizamos recetas de la abuela, 
                    combinadas con técnicas modernas, para asegurar que cada bocado te sepa a tradición. 
                    Nuestro compromiso es simple: **frescura, sabor y calidad** en cada producto.
                </p>
                <p>
                    Apoyamos a agricultores locales y elegimos cuidadosamente cada grano de trigo, 
                    cada huevo y cada fruta que entra en nuestra cocina. ¡Ven y únete a la familia Barrios!
                </p>
            </div>
            <div class="nosotros-cifras" aria-hidden="true">
                <div>
                    <h3>15+</h3>
                    <p>Años de experiencia</p>
                </div>
                <div>
                    <h3>200+</h3>
                    <p>Productos horneados diarios</p>
                </div>
                <div>
                    <h3>98%</h3>
                    <p>Clientes felices</p>
                </div>
            </div>
        </div>
    </section>

    <section class="contacto" id="contacto" aria-label="Formulario y datos de contacto">
        <div class="contacto-container" data-aos="fade-up">

            <h2>Contáctanos</h2>
            <p class="subtexto">Estamos listos para atender tus pedidos y consultas. Escríbenos o visítanos.</p>

            <div class="contacto-grid">

                <div class="contact-card">
                    <img src="img/icono_ubicacion.jpg" alt="Icono Ubicación">
                    <h3>Dirección</h3>
                    <address>Av. Principal 123<br>Tacna, Perú</address> 
                </div>

                <div class="contact-card">
                    <img src="img/icono_telefono.jpg" alt="Icono Teléfono">
                    <h3>Teléfono</h3>
                    <p><a href="tel:+51999999999">999 999 999</a></p>
                </div>

                <div class="contact-card">
                    <img src="img/icono_horario.jpg" alt="Icono Horario">
                    <h3>Horario</h3>
                    <time>Lu - Do: 6:00 am – 10:00 pm</time>
                </div>

                <div class="contact-card contacto-form-card">
                    <img src="img/icono_correo.jpg" alt="Icono Correo Electrónico">
                    <h3>Escríbenos</h3>

                    <form class="contacto-form" action="mailto:contacto@panaderiabarrios.com" method="post" enctype="text/plain">
                        <input type="text" name="nombre" placeholder="Tu nombre" aria-label="Tu nombre" required>
                        <input type="email" name="email" placeholder="Correo electrónico" aria-label="Correo electrónico" required>
                        <textarea name="mensaje" placeholder="Escribe tu mensaje..." rows="4" aria-label="Tu mensaje" required></textarea>
                        <button type="submit" class="btn">Enviar mensaje</button>
                    </form>

                </div>
            </div>
        </div>
    </section>

</main>

<footer class="footer" role="contentinfo">
    <div class="footer-top">

        <div class="footer-logo">
            <img src="" alt="">
            <h2>Panadería Barrios</h2>
            <p>Tradición y sabor desde 1998.</p>
        </div>

        <div class="footer-links">
            <h3>Secciones</h3>
            <a href="#inicio">Inicio</a>
            <a href="#productos">Productos</a>
            <a href="#ofertas">Ofertas</a>
            <a href="#nosotros">Nosotros</a>
            <a href="#contacto">Contacto</a>
        </div>

        <div class="footer-contacto">
            <h3>Contacto</h3>
            <p>📞 <a href="tel:+51999999999">999 999 999</a></p>
            <p>📧 <a href="mailto:contacto@panaderiabarrios.com">contacto@panaderiabarrios.com</a></p>
            <p>📍 Tacna, Perú</p>
            <p>🕒 <time>Lun - Dom: 6:00am - 10:00pm</time></p>
        </div>

        <div class="footer-redes">
            <h3>Síguenos</h3>
            <div class="redes-icons">
                <a href="#" aria-label="Síguenos en Facebook"><img src="img/ico_fb.jpg" alt="Icono Facebook"></a>
                <a href="#" aria-label="Síguenos en Instagram"><img src="img/ico_ig.jpg" alt="Icono Instagram"></a>
                <a href="#" aria-label="Síguenos en TikTok"><img src="img/ico_tiktok.jpg" alt="Icono TikTok"></a>
            </div>
        </div>
    </div>

    <p class="copy">© <?= date('Y') ?> Panadería Barrios — Todos los derechos reservados</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
AOS.init({ duration: 900, once: true });

// Loader: desaparecer cuando cargue la página
window.addEventListener('load', () => {
    const loader = document.getElementById('loader');
    if(loader){
        loader.style.opacity = '0';
        setTimeout(()=> loader.style.display = 'none', 600);
    }
});

// Inicializar Swiper para Ofertas
const swiper = new Swiper(".ofertasSwiper", {
    slidesPerView: 1,
    spaceBetween: 20,
    autoplay: { 
        delay: 2200, 
        disableOnInteraction: false
    },
    pagination: { 
        el: ".swiper-pagination", 
        clickable: true 
    },
    breakpoints: {
        550: { slidesPerView: 2 },
        900: { slidesPerView: 3 }
    }
});
</script>

</body>
</html>
<?php 
// 4. Cerrar conexión después de las consultas (buena práctica)
if (isset($conexion)) { $conexion->close(); } 
?>