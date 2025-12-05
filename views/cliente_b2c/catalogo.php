<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-store me-2"></i>Catálogo de Productos</h2>
        <p class="text-muted">Descubre nuestros deliciosos productos y ofertas especiales</p>
    </div>
</div>

<div class="row">
    <?php 
    $categoria_actual = '';
    foreach ($data['productos'] as $producto): 
        if ($categoria_actual != $producto['categoria_nombre']):
            if ($categoria_actual != '') echo '</div>';
            $categoria_actual = $producto['categoria_nombre'];
    ?>
    <div class="col-12 mt-4">
        <h3 class="border-bottom pb-2"><?php echo $producto['categoria_nombre']; ?></h3>
    </div>
    <div class="row">
    <?php endif; ?>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if ($producto['foto']): ?>
            <img src="<?php echo BASE_URL; ?>public/img/<?php echo $producto['foto']; ?>" class="card-img-top" alt="<?php echo $producto['nombre']; ?>" style="height: 200px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                <p class="card-text"><?php echo $producto['descripcion']; ?></p>
                
                <div class="price-info">
                    <h4 class="text-primary">S/ <?php echo number_format($producto['precio_b2c'], 2); ?></h4>
                    
                    <?php if (isset($producto['ahorro']) && $producto['ahorro'] > 0): ?>
                    <div class="alert alert-success">
                        <small>
                            <strong>¡Ahorra S/ <?php echo number_format($producto['ahorro'], 2); ?>!</strong><br>
                            Precio por separado: S/ <?php echo number_format($producto['precio_separado'], 2); ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <form class="agregar-carrito-form">
                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                    <div class="input-group">
                        <input type="number" name="cantidad" class="form-control" value="1" min="1">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cart-plus me-1"></i>Agregar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php endforeach; ?>
    <?php if ($categoria_actual != '') echo '</div>'; ?>
</div>

<?php include '../app/views/layouts/footer.php'; ?>