<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Mi Carrito de Compras</h2>

    <?php if (empty($data['productos'])): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
            <h4>Tu carrito está vacío</h4>
            <a href="<?= BASE_URL ?>cliente/catalogo" class="btn btn-primary mt-3">Ir al Catálogo</a>
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
                                    <?php 
                                        // Lógica de cálculo visual
                                        $esB2B_Monto = !empty($p['monto_solicitado_entero']);
                                        $precioUnitario = $p['precio_b2c'];
                                        $subtotalItem = 0;

                                        if ($esB2B_Monto) {
                                            $subtotalItem = $p['monto_solicitado_entero'];
                                            // Calcular cantidad aprox visual
                                            $cantidadAprox = floor(($p['monto_solicitado_entero'] / $p['soles_base_b2b']) * $p['unidades_base_b2b']);
                                        } else {
                                            // Es B2C o B2B por unidad (empanadas)
                                            if($_SESSION['usuario_rol'] != 'CLIENTE_ESTANDAR' && $p['soles_base_b2b'] > 0) {
                                                // Precio unitario derivado para B2B
                                                $precioUnitario = $p['soles_base_b2b'] / $p['unidades_base_b2b'];
                                            }
                                            $subtotalItem = $p['cantidad'] * $precioUnitario;
                                        }
                                    ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            
                                            <div>
                                                <h6 class="mb-0"><?= $p['nombre'] ?></h6>
                                                <small class="text-muted"><?= $p['categoria_nombre'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($esB2B_Monto): ?>
                                            <small class="text-muted">
                                                <?= $p['unidades_base_b2b'] ?> u. x S/ <?= number_format($p['soles_base_b2b'], 2) ?>
                                            </small>
                                        <?php else: ?>
                                            S/ <?= number_format($precioUnitario, 2) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($esB2B_Monto): ?>
                                            <!-- Input para B2B Monto -->
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">S/</span>
                                                <input type="number" class="form-control monto-input-cart" 
                                                       value="<?= $p['monto_solicitado_entero'] ?>" 
                                                       data-producto="<?= $p['id_producto'] ?>"
                                                       data-tipo="monto">
                                            </div>
                                            <small class="text-success d-block mt-1">
                                                aprox. <?= $cantidadAprox ?> unid.
                                            </small>
                                        <?php else: ?>
                                            <!-- Input para Cantidad Normal -->
                                            <input type="number" class="form-control form-control-sm cantidad-input" 
                                                   value="<?= $p['cantidad'] ?>" min="1"
                                                   data-producto="<?= $p['id_producto'] ?>"
                                                   data-tipo="cantidad">
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        S/ <?= number_format($subtotalItem, 2) ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-link text-danger p-0 eliminar-producto" 
                                                data-producto="<?= $p['id_producto'] ?>" 
                                                title="Eliminar">
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
                        Proceder al Pago <i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../app/views/layouts/footer.php'; ?>