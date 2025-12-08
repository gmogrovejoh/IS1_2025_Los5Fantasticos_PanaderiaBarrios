<?php include '../app/views/layouts/header.php'; ?>

<div class="container my-4">
    <h2 class="mb-4"><i class="fas fa-bolt text-warning me-2"></i>Realizar Pedido Rápido</h2>

    <div class="row">
    <?php foreach($data['productos'] as $p): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= $p['nombre'] ?></h5>
                    <p class="text-muted small mb-2"><?= $p['categoria_nombre'] ?></p>

                    <!-- Mostrar precio referencial B2B -->
                    <?php 
                        // Calculamos el precio unitario para mostrarlo
                        $precio_unitario = 0;
                        if ($p['unidades_base_b2b'] > 0) {
                            $precio_unitario = $p['soles_base_b2b'] / $p['unidades_base_b2b'];
                        } else {
                            $precio_unitario = $p['precio_b2c'];
                        }
                    ?>

                    <div class="alert alert-light border py-2 mb-3">
                        <?php if($p['unidades_base_b2b'] > 0): ?>
                            <!-- Ejemplo: 6 unid. x S/ 1.00 -->
                            <small class="d-block text-muted">Regla: <b><?= $p['unidades_base_b2b'] ?> u.</b> = <b>S/ <?= number_format($p['soles_base_b2b'], 2) ?></b></small>
                            <div class="fw-bold text-primary mt-1">S/ <?= number_format($precio_unitario, 3) ?> c/u</div>
                        <?php else: ?>
                            <div class="fw-bold text-primary">S/ <?= number_format($p['precio_b2c'], 2) ?> c/u</div>
                        <?php endif; ?>
                    </div>

                    <!-- Input ÚNICO de Cantidad -->
                    <label>Cantidad (Unidades):</label>
                    <div class="input-group">
                        <input type="number" class="form-control input-cantidad" 
                               value="<?= ($p['unidad_minima_b2b'] > 1) ? $p['unidad_minima_b2b'] : 1 ?>" 
                               min="<?= ($p['unidad_minima_b2b'] > 1) ? $p['unidad_minima_b2b'] : 1 ?>"
                               step="1">
                        <button class="btn btn-primary btn-agregar" data-id="<?= $p['id_producto'] ?>">
                            <i class="fas fa-cart-plus me-1"></i> Agregar
                        </button>
                    </div>
                    <?php if($p['unidad_minima_b2b'] > 1): ?>
                        <small class="text-danger">* Mínimo <?= $p['unidad_minima_b2b'] ?> unidades</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>


<?php include '../app/views/layouts/footer.php'; ?>