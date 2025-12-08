<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-credit-card me-2"></i>Finalizar Pedido (B2B)</h2>
        <p class="text-muted">Selecciona la fecha, horario y dirección de entrega si corresponde.</p>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>pedido/procesar">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="tipo_entrega" id="domicilio" value="DOMICILIO" checked>
                        <label class="form-check-label" for="domicilio">
                            Entrega a Domicilio
                            <small class="text-muted d-block">Costo según distrito</small>
                        </label>
                    </div>
                    <div class="ms-3 mb-3">
                        <?php if (!empty($data['direcciones'])): ?>
                            <?php foreach ($data['direcciones'] as $dir): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="id_direccion" value="<?php echo $dir['id_direccion']; ?>" id="dir_<?php echo $dir['id_direccion']; ?>" required>
                                    <label class="form-check-label" for="dir_<?php echo $dir['id_direccion']; ?>">
                                        <?php echo $dir['calle']; ?> <?php echo $dir['numero']; ?>, <?php echo $dir['distrito_nombre']; ?>
                                        <?php if (!empty($dir['referencia'])): ?>
                                            <small class="text-muted d-block"><?php echo $dir['referencia']; ?></small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-map-marker-alt me-2"></i>No tienes direcciones registradas.
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_entrega" id="recojo" value="RECOJO_TIENDA">
                        <label class="form-check-label" for="recojo">
                            Recoger en Tienda
                            <small class="text-muted d-block">Sede Principal Natividad</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Fecha y Horario</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Entrega</label>
                            <input type="date" name="fecha_entrega" class="form-control" 
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   max="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ventana</label>
                            <select name="ventana_entrega" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="MAÑANA">Mañana (5:00 AM - 8:00 AM)</option>
                                <option value="TARDE">Tarde (5:00 PM - 8:00 PM)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Método de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="EFECTIVO" checked>
                        <label class="form-check-label" for="efectivo">Efectivo</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="yape" value="YAPE">
                        <label class="form-check-label" for="yape">Yape</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="plin" value="PLIN">
                        <label class="form-check-label" for="plin">Plin</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong>S/ <?php echo number_format($data['subtotal'], 2); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Costo de envío:</span>
                        <strong>S/ 0.00</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span><strong>Total:</strong></span>
                        <strong class="text-primary">S/ <?php echo number_format($data['subtotal'], 2); ?></strong>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success w-100" type="submit">
                        <i class="fas fa-check me-2"></i>Confirmar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include '../app/views/layouts/footer.php'; ?>