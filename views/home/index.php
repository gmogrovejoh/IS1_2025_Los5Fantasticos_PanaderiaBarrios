<?php 
$titulo = "Panadería Barrios - Inicio";
include 'views/layout/header.php'; 
?>

<!-- Hero Section -->
<div class="hero-section bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 mb-3">Bienvenidos a Panadería Barrios</h1>
        <p class="lead mb-4">Tradición familiar, sabor auténtico. Los mejores productos de panadería y pastelería de Tacna.</p>
        <a href="index.php?controller=producto&action=catalogo" class="btn btn-light btn-lg">
            <i class="fas fa-shopping-bag me-2"></i>Ver Productos
        </a>
    </div>
</div>

<!-- Categorías -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Nuestras Categorías</h2>
        <div class="row">
            <?php foreach ($categorias as $categoria): ?>
            <div class="col-md-4 col-lg-2 mb-4">
                <div class="card h-100 text-center categoria-card">
                    <div class="card-body">
                        <i class="fas fa-bread-slice fa-3x text-primary mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($categoria['nombre']) ?></h5>
                        <a href="index.php?controller=producto&action=catalogo&categoria=<?= $categoria['id_categoria'] ?>" 
                            class="btn btn-outline-primary btn-sm">Ver Productos</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Productos Destacados -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Productos Destacados</h2>
        <div class="row">
            <?php foreach ($productos_destacados as $producto): ?>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 producto-card">
                    <img src="public/images/<?= $producto['foto'] ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre']) ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                        <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">S/ <?= number_format($producto['precio'], 2) ?></span>
                            <span class="badge bg-success"><?= $producto['puntos'] ?> puntos</span>
                        </div>
                        <button class="btn btn-primary mt-2 agregar-carrito" data-id="<?= $producto['id_producto'] ?>">
                            <i class="fas fa-cart-plus me-1"></i>Agregar
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Características -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                <h4>Horneado Diario</h4>
                <p>Productos frescos horneados todos los días desde las 5:00 AM</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                <h4>Delivery Gratis</h4>
                <p>Entrega gratuita en pedidos mayores a S/ 30 en toda la ciudad</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-award fa-3x text-primary mb-3"></i>
                <h4>Calidad Garantizada</h4>
                <p>Más de 20 años de experiencia en panadería y pastelería</p>
            </div>
        </div>
    </div>
</section>

<?php include 'views/layout/footer.php'; ?>