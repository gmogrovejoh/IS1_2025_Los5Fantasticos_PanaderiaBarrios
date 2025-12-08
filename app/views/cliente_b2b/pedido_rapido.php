<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-bolt me-2"></i>Pedido Rápido B2B</h2>
        <p class="text-muted">Ingresa los montos en soles para calcular automáticamente las cantidades</p>
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
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php if ($producto['foto']): ?>
                        <img src="<?php echo BASE_URL; ?>public/img/<?php echo $producto['foto']; ?>" class="img-fluid rounded" alt="<?php echo $producto['nombre']; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h5><?php echo $producto['nombre']; ?></h5>
                        <p class="text-muted small"><?php echo $producto['descripcion']; ?></p>
                        
                        <div class="pricing-info mb-3">
                            <small class="text-muted">
                                Regla B2B: <?php echo $producto['unidades_base_b2b']; ?> unidades = S/ <?php echo number_format($producto['soles_base_b2b'], 2); ?>
                            </small>
                        </div>
                        
                        <?php if ($producto['categoria_nombre'] == 'Pastelería y Repostería' && $producto['unidad_minima_b2b'] > 1): ?>
                        <!-- Para empanadas: selector de cantidad -->
                        <div class="input-group">
                            <span class="input-group-text">Cantidad:</span>
                            <input type="number" class="form-control cantidad-empanada" 
                                   min="<?php echo $producto['unidad_minima_b2b']; ?>" 
                                   step="<?php echo $producto['unidad_minima_b2b']; ?>"
                                   data-producto="<?php echo $producto['id_producto']; ?>"
                                   data-precio="<?php echo $producto['soles_base_b2b'] / $producto['unidades_base_b2b']; ?>">
                            <button class="btn btn-primary agregar-empanada" 
                                    data-producto="<?php echo $producto['id_producto']; ?>">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                        <small class="text-muted">Mínimo: <?php echo $producto['unidad_minima_b2b']; ?> unidades</small>
                        
                        <?php else: ?>
                        <!-- Para panes: input de monto -->
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" class="form-control monto-input" value="25"
                                   min="25"  placeholder="Ingrese monto entero"
                                   data-producto="<?php echo $producto['id_producto']; ?>"
                                   data-unidades="<?php echo $producto['unidades_base_b2b']; ?>"
                                   data-soles="<?php echo $producto['soles_base_b2b']; ?>">
                            <button class="btn btn-primary agregar-monto" 
                                    data-producto="<?php echo $producto['id_producto']; ?>">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                        <div class="cantidad-calculada mt-2" style="display: none;">
                            <small class="text-success">
                                <strong>Cantidad calculada: <span class="cantidad-valor">0</span> unidades</strong>
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php endforeach; ?>
    <?php if ($categoria_actual != '') echo '</div>'; ?>
</div>

<div class="fixed-bottom bg-light border-top p-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span>Productos en carrito: <strong id="items-carrito">0</strong></span>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?php echo BASE_URL; ?>cliente/carrito" class="btn btn-success">
                    <i class="fas fa-shopping-cart me-2"></i>Ver Carrito
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>