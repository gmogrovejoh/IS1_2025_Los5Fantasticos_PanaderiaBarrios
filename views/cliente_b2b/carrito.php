<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-shopping-cart me-2"></i>Mi Carrito</h2>
    </div>
</div>

<?php if (empty($data['productos'])): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>Tu carrito está vacío.
    <a href="<?php echo BASE_URL; ?>cliente/catalogo" class="alert-link">¡Explora nuestro catálogo!</a>
</div>
<?php else: ?>

<div class="row">
    <div class="col-md-8">
        <?php foreach ($data['productos'] as $producto): ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <?php if ($producto['foto']): ?>
                        <img src="<?php echo BASE_URL; ?>public/img/<?php echo $producto['foto']; ?>" class="img-fluid rounded" alt="<?php echo $producto['nombre']; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <h5><?php echo $producto['nombre']; ?></h5>
                        <p class="text-muted"><?php echo $producto['categoria_nombre']; ?></p>
                    </div>
                    <div class="col-md-2">
                        <label>Cantidad:</label>
                        <input type="number" class="form-control cantidad-input" 
                               value="<?php echo $producto['cantidad']; ?>" 
                               min="1" 
                               data-producto="<?php echo $producto['id_producto']; ?>">
                    </div>
                    <div class="col-md-2">
                        <strong>S/ <?php echo number_format($producto['precio_b2c'], 2); ?></strong>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger btn-sm eliminar-producto" 
                                data-producto="<?php echo $producto['id_producto']; ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Resumen del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <strong>S/ <?php echo number_format($data['subtotal'], 2); ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span><strong>Total:</strong></span>
                    <strong class="text-primary">S/ <?php echo number_format($data['subtotal'], 2); ?></strong>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo BASE_URL; ?>cliente/checkout" class="btn btn-success w-100">
                    <i class="fas fa-credit-card me-2"></i>Proceder al Pago
                </a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<?php include '../app/views/layouts/footer.php'; ?>