<?php 
$titulo = 'Checkout - Panadería Barrios';
include APP_NAME . 'views/layouts/header.php'; 
?>

<h2><i class="fas fa-credit-card me-2"></i>Finalizar Pedido</h2>

<form method="POST" action="<?= BASE_URL ?>pedido/confirmar" class="needs-validation" novalidate>
    <div class="row">
        <div class="col-lg-8">
            <!-- Resumen de productos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($productos as $producto): ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong><?= $producto['nombre'] ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?php if ($producto['cantidad']): ?>
                                        Cantidad: <?= $producto['cantidad'] ?> × S/ <?= number_format($producto['precio_b2c'], 2) ?>
                                    <?php else: ?>
                                        Monto: S/ <?= $producto['monto_solicitado_entero'] ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <strong>S/ <?= number_format($producto['monto_solicitado_entero'] ?? ($producto['precio_b2c'] * $producto['cantidad']), 2) ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Opciones de entrega -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Método de Entrega</h5>
                </div>
                <div class="card-body">
                    <?php if ($_SESSION['usuario_rol'] !== 'CLIENTE_ESTANDAR' && !empty($direcciones)): ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="tipo_entrega" value="DOMICILIO" id="entrega_domicilio" required>
                            <label class="form-check-label" for="entrega_domicilio">
                                <strong>Entrega a Domicilio</strong>
                                <small class="text-muted d-block">Costo adicional según zona</small>
                            </label>
                        </div>
                        
                        <div id="direcciones_container" style="display: none;" class="ms-4 mb-3">
                            <label class="form-label">Selecciona una dirección:</label>
                            <?php foreach ($direcciones as $direccion): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="direccion_id" value="<?= $direccion['id_direccion'] ?>" id="dir_<?= $direccion['id_direccion'] ?>">
                                    <label class="form-check-label" for="dir_<?= $direccion['id_direccion'] ?>">
                                        <?= $direccion['alias'] ? $direccion['alias'] . ' - ' : '' ?>
                                        <?= $direccion['calle'] ?> <?= $direccion['numero'] ?>, <?= $direccion['distrito_nombre'] ?>
                                        <?php if ($direccion['referencia']): ?>
                                            <small class="text-muted d-block"><?= $direccion['referencia'] ?></small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_entrega" value="RECOJO_TIENDA" id="entrega_tienda" required <?= $_SESSION['usuario_rol'] === 'CLIENTE_ESTANDAR' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="entrega_tienda">
                            <strong>Recoger en Tienda</strong>
                            <small class="text-muted d-block">Sede Principal Natividad - Sin costo adicional</small>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Fecha y horario -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Fecha y Horario de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" 
                                   value="<?= $fechaEntrega ?>" min="<?= date('Y-m-d') ?>" 
                                   max="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ventana_entrega" class="form-label">Horario</label>
                            <select class="form-control" id="ventana_entrega" name="ventana_entrega" required>
                                <option value="">Seleccionar horario</option>
                                <?php foreach ($ventanasDisponibles as $ventana): ?>
                                    <option value="<?= $ventana ?>">
                                        <?= $ventana === 'MAÑANA' ? 'Mañana (5:00 AM - 8:00 AM)' : 'Tarde (5:00 PM - 8:00 PM)' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Método de pago -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Método de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="EFECTIVO" checked>
                        <label class="form-check-label" for="efectivo">
                            <i class="fas fa-money-bill me-2"></i>Efectivo (Pagar en tienda o al recibir)
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="yape" value="YAPE">
                        <label class="form-check-label" for="yape">
                            <i class="fas fa-mobile-alt me-2"></i>Yape
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="plin" value="PLIN">
                        <label class="form-check-label" for="plin">
                            <i class="fas fa-mobile-alt me-2"></i>Plin
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen Final</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>S/ <?= number_format($subtotal, 2) ?></strong>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Costo de envío:</span>
                        <span id="costo-envio">S/ <?= number_format($costoEnvio, 2) ?></span>
                    </div>
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary h5" id="total-final">S/ <?= number_format($total, 2) ?></strong>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 btn-lg">
                        <i class="fas fa-check me-2"></i>Confirmar Pedido
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>carrito/ver" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Carrito
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Información del cliente -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="fas fa-user me-2"></i>Información del Cliente</h6>
                    <p class="mb-1"><strong>Nombre:</strong> <?= $cliente['nombre'] ?> <?= $cliente['apellidos'] ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?= $cliente['email'] ?></p>
                    <p class="mb-0"><strong>Comprobante:</strong> 
                        <?= $_SESSION['usuario_rol'] === 'EMPRESA_FACTURA' ? 'Factura' : 'Boleta' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar mostrar/ocultar direcciones
    const entregaDomicilio = document.getElementById('entrega_domicilio');
    const entregaTienda = document.getElementById('entrega_tienda');
    const direccionesContainer = document.getElementById('direcciones_container');
    
    if (entregaDomicilio) {
        entregaDomicilio.addEventListener('change', function() {
            if (this.checked) {
                direccionesContainer.style.display = 'block';
            }
        });
    }
    
    if (entregaTienda) {
        entregaTienda.addEventListener('change', function() {
            if (this.checked) {
                direccionesContainer.style.display = 'none';
            }
        });
    }
    
    // Actualizar costo de envío según tipo de entrega
    document.querySelectorAll('input[name="tipo_entrega"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const costoEnvioElement = document.getElementById('costo-envio');
            const totalElement = document.getElementById('total-final');
            const subtotal = <?= $subtotal ?>;
            
            if (this.value === 'RECOJO_TIENDA') {
                costoEnvioElement.textContent = 'S/ 0.00';
                totalElement.textContent = 'S/ ' + subtotal.toFixed(2);
            } else {
                costoEnvioElement.textContent = 'S/ <?= number_format($costoEnvio, 2) ?>';
                totalElement.textContent = 'S/ <?= number_format($total, 2) ?>';
            }
        });
    });
});
</script>

<?php include APP_NAME . 'views/layouts/footer.php'; ?>