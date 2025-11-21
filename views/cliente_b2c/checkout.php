<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-credit-card me-2"></i>Finalizar Pedido</h2>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>pedido/procesar">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Opciones de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_entrega" id="recojo_tienda" value="RECOJO_TIENDA" checked>
                        <label class="form-check-label" for="recojo_tienda">
                            <strong>Recoger en Tienda</strong><br>
                            <small class="text-muted">Sede Principal Natividad - Sin costo adicional</small>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Fecha y Horario de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" 
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   max="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ventana_entrega" class="form-label">Horario</label>
                            <select class="form-control" id="ventana_entrega" name="ventana_entrega" required>
                                <option value="">Seleccionar horario</option>
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
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="EFECTIVO" checked>
                        <label class="form-check-label" for="efectivo">
                            Efectivo (Pagar en tienda)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="yape" value="YAPE">
                        <label class="form-check-label" for="yape">
                            Yape
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="plin" value="PLIN">
                        <label class="form-check-label" for="plin">
                            Plin
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($data['productos'] as $producto): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span><?php echo $producto['nombre']; ?> (<?php echo $producto['cantidad']; ?>)</span>
                        <span>S/ <?php echo number_format($producto['cantidad'] * $producto['precio_b2c'], 2); ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <hr>
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
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-2"></i>Confirmar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include '../app/views/layouts/footer.php'; ?>