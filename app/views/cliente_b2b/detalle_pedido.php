<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-file-invoice me-2"></i>
            Pedido #<?= str_pad($data['pedido']['id_pedido'], 6, '0', STR_PAD_LEFT) ?>
        </h2>
        <a href="<?= BASE_URL ?>pedido/historial" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="row">
        <!-- Columna Izquierda: Detalles Generales -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información del Pedido</h5>
                </div>
                <div class="card-body">
                    <p><strong>Fecha Registro:</strong><br> <?= date('d/m/Y h:i A', strtotime($data['pedido']['fecha_registro'])) ?></p>
                    <p><strong>Estado:</strong><br> 
                        <span class="badge bg-primary"><?= $data['pedido']['estado'] ?></span>
                    </p>
                    <p><strong>Total:</strong><br> <span class="text-success fw-bold fs-5">S/ <?= number_format($data['pedido']['costo_total'], 2) ?></span></p>
                    <p><strong>Comprobante:</strong><br> <?= $data['pedido']['tipo_comprobante'] ?></p>
                </div>
            </div>
        </div>

        <!-- Columna Central: Entrega -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Datos de Entrega</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tipo:</strong> <?= $data['pedido']['tipo_entrega'] ?></p>
                    <p><strong>Fecha Entrega:</strong><br> <?= date('d/m/Y', strtotime($data['pedido']['fecha_entrega'])) ?></p>
                    <p><strong>Turno:</strong> <?= $data['pedido']['ventana_entrega'] ?></p>
                    
                    <hr>
                    
                    <?php if ($data['pedido']['tipo_entrega'] == 'DOMICILIO'): ?>
                        <p class="mb-1"><i class="fas fa-map-marker-alt text-danger me-1"></i> <strong>Dirección:</strong></p>
                        <p class="mb-1"><?= $data['pedido']['calle'] ?> #<?= $data['pedido']['numero'] ?></p>
                        <small class="text-muted"><?= $data['pedido']['distrito_nombre'] ?></small>
                        <?php if(!empty($data['pedido']['referencia'])): ?>
                            <br><small class="text-muted">Ref: <?= $data['pedido']['referencia'] ?></small>
                        <?php endif; ?>
                    <?php else: ?>
                        <p><i class="fas fa-store text-warning me-1"></i> <strong>Recojo en Sede:</strong></p>
                        <p><?= $data['pedido']['sede_nombre'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Acciones (Opcional) -->
        <div class="col-md-4 mb-4">
             <div class="card shadow-sm h-100 bg-light border-0">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-print fa-3x text-secondary mb-3"></i>
                    <button onclick="window.print()" class="btn btn-secondary mb-2" <?php if($data['pedido']['estado'] == 'PENDIENTE_PAGO'){ echo 'disabled';} ?>>
                        Imprimir Comprobante
                    </button>
                    <?php if($data['pedido']['estado'] == 'PENDIENTE_PAGO'): ?>
                        <button class="btn btn-success">
                            Contactar para Pago al <a href="https://wa.me/988954525">988954525</a>
                        </button>
                    <?php endif; ?>
                </div>
             </div>
        </div>
    </div>

    <!-- TABLA DE PRODUCTOS -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Items del Pedido</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio Unit. (Congelado)</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['detalles'] as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($item['foto']): ?>
                                        <img src="<?= BASE_URL ?>public/img/<?= $item['foto'] ?>" class="rounded me-3" style="width: 40px;">
                                    <?php endif; ?>
                                    <strong><?= $item['nombre'] ?></strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    <?= $item['cantidad'] ?> unid.
                                </span>
                            </td>
                            <td class="text-end">
                                S/ <?= number_format($item['precio_unitario_congelado'], 3) ?>
                            </td>
                            <td class="text-end fw-bold">
                                S/ <?= number_format($item['subtotal'], 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="text-end">Subtotal Productos:</td>
                            <td class="text-end">S/ <?= number_format($data['pedido']['subtotal_productos'], 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Costo de Envío:</td>
                            <td class="text-end">S/ <?= number_format($data['pedido']['costo_envio'], 2) ?></td>
                        </tr>
                        <tr class="table-active">
                            <td colspan="3" class="text-end fw-bold fs-5">TOTAL PAGAR:</td>
                            <td class="text-end fw-bold fs-5 text-success">S/ <?= number_format($data['pedido']['costo_total'], 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, nav, footer, .no-print { display: none !important; }
    .card { border: 1px solid #ddd !important; }
}
</style>

<?php include '../app/views/layouts/footer.php'; ?>