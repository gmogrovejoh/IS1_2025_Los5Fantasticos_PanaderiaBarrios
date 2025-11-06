<?php 
$titulo = "Carrito de Compras - Panadería Barrios";
include 'views/layout/header.php'; 
?>

<div class="container my-5">
    <h2><i class="fas fa-shopping-cart me-2"></i>Mi Carrito</h2>
    
    <?php if (empty($productos)): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>Tu carrito está vacío</h4>
            <p class="text-muted">Agrega algunos productos deliciosos a tu carrito</p>
            <a href="index.php?controller=producto&action=catalogo" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-1"></i>Ver Productos
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($productos as $producto): ?>
                        <div class="row align-items-center mb-3 pb-3 border-bottom" data-producto="<?= $producto['id_producto'] ?>">
                            <div class="col-md-2">
                                <img src="public/images/<?= $producto['foto'] ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                            </div>
                            <div class="col-md-4">
                                <h6><?= htmlspecialchars($producto['nombre']) ?></h6>
                                <small class="text-muted">S/ <?= number_format($producto['precio'], 2) ?> c/u</small>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary btn-sm cantidad-btn" data-action="decrease">-</button>
                                    <input type="number" class="form-control text-center cantidad-input" 
                                           value="<?= $producto['cantidad'] ?>" min="1" readonly>
                                    <button class="btn btn-outline-secondary btn-sm cantidad-btn" data-action="increase">+</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <strong>S/ <?= number_format($producto['subtotal'], 2) ?></strong>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-outline-danger btn-sm eliminar-producto">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">S/ <?= number_format($total, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Envío:</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total">S/ <?= number_format($total, 2) ?></strong>
                        </div>
                        
                        <div class="d-grid">
                            <a href="index.php?controller=pedido&action=checkout" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-1"></i>Proceder al Pago
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>