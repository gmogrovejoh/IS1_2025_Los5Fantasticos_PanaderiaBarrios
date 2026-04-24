<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Mi Carrito de Compras</h2>

    <?php if (empty($data['productos'])): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
            <h4>Tu carrito está vacío</h4>
            <a href="<?= BASE_URL ?>cliente/pedidoRapido" class="btn btn-primary mt-3">Ir al Catálogo</a>
        </div>
    <?php else: ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 40%">Producto</th>
                                    <th style="width: 20%">Precio/Regla</th>
                                    <th style="width: 20%">Cantidad/Monto</th>
                                    <th style="width: 15%">Subtotal</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['productos'] as $p): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($p['foto']): ?>
                                                    <img src="<?= BASE_URL ?>public/img/<?= $p['foto'] ?>" class="rounded me-3" style="width: 50px;">
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?= $p['nombre'] ?></h6>
                                                    <small class="text-muted"><?= $p['categoria_nombre'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                                // Calcular precio unitario para mostrar
                                                if ($p['unidades_base_b2b'] > 0) {
                                                    $pu = $p['soles_base_b2b'] / $p['unidades_base_b2b'];
                                                    echo "S/ " . number_format($pu, 3);
                                                } else {
                                                    echo "S/ " . number_format($p['precio_b2c'], 2);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <!-- Solo Input de Cantidad -->
                                            <input type="number" class="form-control form-control-sm cantidad-input-cart" 
                                                value="<?= $p['cantidad'] ?>" min="1"
                                                data-producto="<?= $p['id_producto'] ?>">
                                        </td>
                                        <td class="fw-bold text-primary">
                                            <?php 
                                                if ($p['unidades_base_b2b'] > 0) {
                                                    $subtotal = $p['cantidad'] * ($p['soles_base_b2b'] / $p['unidades_base_b2b']);
                                                } else {
                                                    $subtotal = $p['cantidad'] * $p['precio_b2c'];
                                                }
                                                echo "S/ " . number_format($subtotal, 2);
                                            ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-link text-danger eliminar-producto" data-producto="<?= $p['id_producto'] ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="<?= BASE_URL ?><?= $_SESSION['usuario_rol'] == 'CLIENTE_ESTANDAR' ? 'cliente/catalogo' : 'cliente/pedidoRapido' ?>" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
                </a>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-4">Resumen del Pedido</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold">S/ <?= number_format($data['subtotal'], 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>IGV (incluido)</span>
                        <span class="text-muted">S/ <?= number_format($data['subtotal'] * 0.18, 2) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5">Total</span>
                        <span class="h4 text-success">S/ <?= number_format($data['subtotal'], 2) ?></span>
                    </div>
                    
                    <a href="<?= BASE_URL ?>cliente/checkout" class="btn btn-success w-100 btn-lg shadow">
                        Proceder pedido <i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../app/views/layouts/footer.php'; ?>