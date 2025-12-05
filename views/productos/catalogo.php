<?php 
$titulo = "Catálogo de Productos - Panadería Barrios";
include 'views/layout/header.php'; 
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar de categorías -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-filter me-2"></i>Filtros</h5>
                </div>
                <div class="card-body">
                    <!-- Búsqueda -->
                    <form method="GET" class="mb-3">
                        <input type="hidden" name="controller" value="producto">
                        <input type="hidden" name="action" value="catalogo">
                        <div class="input-group">
                            <input type="text" class="form-control" name="buscar" placeholder="Buscar productos..." 
                                   value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Categorías -->
                    <h6>Categorías</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php?controller=producto&action=catalogo" 
                               class="text-decoration-none <?= !isset($_GET['categoria']) ? 'fw-bold' : '' ?>">
                               Todas las categorías</a></li>
                        <?php foreach ($categorias as $categoria): ?>
                        <li><a href="index.php?controller=producto&action=catalogo&categoria=<?= $categoria['id_categoria'] ?>" 
                               class="text-decoration-none <?= ($_GET['categoria'] ?? '') == $categoria['id_categoria'] ? 'fw-bold text-primary' : '' ?>">
                               <?= htmlspecialchars($categoria['nombre']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Productos -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><?= htmlspecialchars($titulo) ?></h2>
                <span class="text-muted"><?= count($productos) ?> productos encontrados</span>
            </div>
            
            <?php if (empty($productos)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No se encontraron productos</h4>
                    <p class="text-muted">Intenta con otros términos de búsqueda o categorías</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($productos as $producto): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 producto-card">
                            <img src="public/images/<?= $producto['foto'] ?>" class="card-img-top" 
                                    alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                                    style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($producto['descripcion'], 0, 80)) ?>...</p>
                                <small class="text-muted mb-2"><?= htmlspecialchars($producto['categoria_nombre']) ?></small>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="h5 text-primary mb-0">S/ <?= number_format($producto['precio'], 2) ?></span>
                                    <span class="badge bg-success"><?= $producto['puntos'] ?> puntos</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary agregar-carrito" data-id="<?= $producto['id_producto'] ?>">
                                        <i class="fas fa-cart-plus me-1"></i>Agregar al Carrito
                                    </button>
                                    <a href="index.php?controller=producto&action=detalle&id=<?= $producto['id_producto'] ?>" 
                                        class="btn btn-outline-secondary btn-sm">Ver Detalles</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>