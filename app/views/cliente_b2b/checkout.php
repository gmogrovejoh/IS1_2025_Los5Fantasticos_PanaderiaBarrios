<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>Finalizar Pedido</h2>
    
    <form method="POST" action="<?= BASE_URL ?>pedido/procesar">
        <div class="row">
            <!-- COLUMNA IZQUIERDA -->
            <div class="col-md-8">
                <!-- 1. TIPO DE ENTREGA -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">1. Método de Entrega</h5>
                    </div>
                    <div class="card-body">
                        <!-- Opción Recojo -->
                        <div class="form-check p-3 border rounded mb-2 cursor-pointer">
                            <input class="form-check-input" type="radio" name="tipo_entrega" id="recojo" value="RECOJO_TIENDA" checked>
                            <label class="form-check-label w-100" for="recojo">
                                <strong><i class="fas fa-store text-warning me-2"></i>Recoger en Tienda</strong>
                                <small class="d-block text-muted">Sede Principal Natividad - Sin costo</small>
                            </label>
                        </div>

                        <!-- Opción Domicilio -->
                        <div class="form-check p-3 border rounded cursor-pointer">
                            <input class="form-check-input" type="radio" name="tipo_entrega" id="domicilio" value="DOMICILIO">
                            <label class="form-check-label w-100" for="domicilio">
                                <strong><i class="fas fa-truck text-primary me-2"></i>Delivery</strong>
                                <small class="d-block text-muted">Envío gratis según tu zona y monto de compra</small>
                            </label>
                        </div>

                        <!-- LISTA DE DIRECCIONES (Oculta por defecto) -->
                        <div id="contenedor-direcciones" class="mt-3 ms-4" style="display: none;">
                            <h6 class="text-muted mb-2">Selecciona tu dirección:</h6>
                            <?php if (empty($data['direcciones'])): ?>
                                <div class="alert alert-warning">
                                    No tienes direcciones. <a href="<?= BASE_URL ?>cliente/perfil">Agrega una aquí</a>.
                                </div>
                            <?php else: ?>
                                <?php foreach ($data['direcciones'] as $index => $dir): ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="id_direccion" 
                                               id="dir_<?= $dir['id_direccion'] ?>" 
                                               value="<?= $dir['id_direccion'] ?>"
                                               <?= $index === 0 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="dir_<?= $dir['id_direccion'] ?>">
                                            <strong><?= $dir['alias'] ?></strong> - <?= $dir['calle'] ?> #<?= $dir['numero'] ?> 
                                            <span class="badge bg-light text-dark"><?= $dir['distrito_nombre'] ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div id="promo-envio-container" class="mt-3 p-3 bg-light rounded border" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small id="mensaje-promo-texto" class="text-muted">Calculando promoción...</small>
                                <i class="fas fa-shipping-fast text-primary"></i>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div id="barra-promo" class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                    role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 2. FECHA Y HORA (Igual que antes) -->
                <div class="card mb-4 shadow-sm">
                     <div class="card-header bg-white"><h5 class="mb-0">2. Fecha de Entrega</h5></div>
                     <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Fecha</label>
                                <!-- CAMBIO: min y value configurados para MAÑANA (+1 day) -->
                                <input type="date" name="fecha_entrega" class="form-control" 
                                    required 
                                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                                    value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                <small class="text-muted">Pedidos con 24h de anticipación</small>
                            </div>
                            <div class="col-md-6">
                                <label>Horario</label>
                                <select name="ventana_entrega" class="form-select" required>
                                    <option value="MAÑANA">Mañana (5:00 AM - 8:00 AM)</option>
                                    <option value="TARDE">Tarde (5:00 PM - 8:00 PM)</option>
                                </select>
                            </div>
                        </div>
                     </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: RESUMEN -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Resumen de Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>S/ <?= number_format($data['subtotal'], 2) ?></strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Envío:</span>
                            <!-- ID PARA JS -->
                            <span id="costo-envio-display">S/ 0.00</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5">Total:</span>
                            <!-- ID PARA JS -->
                            <strong class="fs-4 text-success" id="total-pagar-display">
                                S/ <?= number_format($data['subtotal'], 2) ?>
                            </strong>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2">
                            <i class="fas fa-check-circle me-2"></i> Confirmar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include '../app/views/layouts/footer.php'; ?>