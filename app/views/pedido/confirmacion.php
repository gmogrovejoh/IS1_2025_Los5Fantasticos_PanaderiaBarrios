<?php 
$titulo = 'Pedido Confirmado - Panadería Barrios';
include APP_NAME . 'views/layouts/header.php'; 
?>

<div class="text-center mb-4">
    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
    <h2 class="text-success">¡Pedido Confirmado!</h2>
    <p class="lead">Tu pedido ha sido registrado exitosamente</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>
                    Detalles del Pedido #<?= str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT) ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2"></i>Información del Pedido</h6>
                        <p class="mb-1"><strong>Fecha de registro:</strong> <?= date('d/m/Y H:i', strtotime($pedido['fecha_registro'])) ?></p>
                        <p class="mb-1"><strong>Estado:</strong> <span class="badge bg-warning"><?= $pedido['estado'] ?></span></p>
                        <p class="mb-1"><strong>Tipo de comprobante:</strong> <?= $pedido['tipo_comprobante'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-truck me-2"></i>Entrega</h6>
                        <p class="mb-1"><strong>Tipo:</strong> <?= $pedido['tipo_entrega'] === 'RECOJO_TIENDA' ? 'Recojo en Tienda' : 'Entrega a Domicilio' ?></p>
                        <p class="mb-1"><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($pedido['fecha_entrega'])) ?></p>
                        <p class="mb-1"><strong>Horario:</strong> <?= $pedido['ventana_entrega'] === 'MAÑANA' ? 'Mañana (5-8 AM)' : 'Tarde (5-8 PM)' ?></p>
                        <?php if ($pedido['tipo_entrega'] === 'DOMICILIO' && $pedido['calle']): ?>
                            <p class="mb-1"><strong>Dirección:</strong> <?= $pedido['calle'] ?> <?= $pedido['numero'] ?></p>
                            <?php if ($pedido['referencia']): ?>
                                <p class="mb-1"><strong>Referencia:</strong> <?= $pedido['referencia'] ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="mb-1"><strong>Sede:</strong> <?= $pedido['sede_nombre'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <h6><i class="fas fa-shopping-bag me-2"></i>Productos Pedidos</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td>
                                        <strong><?= $producto['nombre'] ?></strong>
                                        <?php if ($producto['monto_solicitado_entero']): ?>
                                            <br><small class="text-muted">Monto B2B: S/ <?= number_format($producto['monto_solicitado_entero'], 2) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $producto['cantidad'] ?></td>
                                    <td class="text-end">S/ <?= number_format($producto['precio_unitario_congelado'], 2) ?></td>
                                    <td class="text-end">S/ <?= number_format($producto['subtotal'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Subtotal productos:</th>
                                <th class="text-end">S/ <?= number_format($pedido['subtotal_productos'], 2) ?></th>
                            </tr>
                            <tr>
                                <th colspan="3">Costo de envío:</th>
                                <th class="text-end">S/ <?= number_format($pedido['costo_envio'], 2) ?></th>
                            </tr>
                            <tr class="table-success">
                                <th colspan="3">TOTAL:</th>
                                <th class="text-end h5">S/ <?= number_format($pedido['costo_total'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-info-circle me-2"></i>Próximos Pasos</h6>
                    <ul class="mb-0">
                        <li>Recibirás una confirmación por email con los detalles del pedido</li>
                        <li>Te contactaremos para coordinar el pago y la entrega</li>
                        <li>Puedes consultar el estado de tu pedido en "Mis Pedidos"</li>
                        <?php if ($pedido['tipo_entrega'] === 'RECOJO_TIENDA'): ?>
                            <li>Recuerda traer tu DNI al recoger el pedido en tienda</li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?= BASE_URL ?>pedido/historial" class="btn btn-primary me-2">
                        <i class="fas fa-history me-2"></i>Ver Mis Pedidos
                    </a>
                    <?php if ($_SESSION['usuario_rol'] === 'CLIENTE_ESTANDAR'): ?>
                        <a href="<?= BASE_URL ?>cliente/catalogo" class="btn btn-outline-primary">
                            <i class="fas fa-store me-2"></i>Seguir Comprando
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>cliente/pedidoRapido" class="btn btn-outline-primary">
                            <i class="fas fa-bolt me-2"></i>Nuevo Pedido Rápido
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_NAME . 'views/layouts/footer.php'; ?>