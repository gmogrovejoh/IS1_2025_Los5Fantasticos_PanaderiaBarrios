<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <!-- Encabezado con Botones de Acción -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Pedido #<?= str_pad($data['pedido']['id_pedido'], 6, '0', STR_PAD_LEFT) ?></h2>
            <span class="badge bg-secondary"><?= date('d/m/Y H:i', strtotime($data['pedido']['fecha_registro'])) ?></span>
        </div>
        <div>
            <!-- Botón Editar -->
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalEditarPedido">
                <i class="fas fa-edit me-2"></i>Editar
            </button>
            
            <!-- Botón Eliminar -->
            <a href="<?= BASE_URL ?>admin/eliminarPedido/<?= $data['pedido']['id_pedido'] ?>" 
               class="btn btn-danger"
               onclick="return confirm('¿Estás seguro de ELIMINAR este pedido?\nEsta acción es irreversible.')">
                <i class="fas fa-trash me-2"></i>Eliminar
            </a>
            
            <a href="<?= BASE_URL ?>admin/gestionPedidos" class="btn btn-outline-secondary ms-2">Volver</a>
        </div>
    </div>

    <div class="row">
        <!-- Datos del Cliente  -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm h-100">
                <div class="card-header fw-bold bg-light">
                    <i class="fas fa-user me-2"></i>Datos del Cliente
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= $data['pedido']['cliente_nombre'] ?> <?= $data['pedido']['cliente_apellidos'] ?></h5>
                    <hr>
                    <p class="mb-1"><strong>Empresa:</strong> <?= $data['pedido']['razon_social'] ?? 'Particular' ?></p>
                    <p class="mb-1"><strong>RUC:</strong> <?= $data['pedido']['cliente_ruc'] ?? 'N/A' ?></p>
                    <p class="mb-1"><strong>Teléfono:</strong> <a href="tel:<?= $data['pedido']['telefono'] ?>"><?= $data['pedido']['telefono'] ?></a></p>
                    <p class="mb-0"><strong>Email:</strong> <a href="mailto:<?= $data['pedido']['email'] ?>"><?= $data['pedido']['email'] ?></a></p>
                </div>
            </div>
        </div>

        <!-- Datos de Entrega y Estado -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm h-100">
                <div class="card-header fw-bold bg-light">
                    <i class="fas fa-truck me-2"></i>Logística
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Estado Actual:</label><br>
                        <?php 
                            $bg = 'secondary';
                            $est = $data['pedido']['estado'];
                            if($est == 'PENDIENTE_PAGO') $bg = 'warning text-dark';
                            if($est == 'PAGADO') $bg = 'info text-dark';
                            if($est == 'ENTREGADO') $bg = 'success';
                            if($est == 'CANCELADO') $bg = 'danger';
                        ?>
                        <span class="badge bg-<?= $bg ?> fs-6"><?= $est ?></span>
                    </div>

                    <p class="mb-1"><strong>Fecha Entrega:</strong> <?= date('d/m/Y', strtotime($data['pedido']['fecha_entrega'])) ?></p>
                    <p class="mb-1"><strong>Turno:</strong> <?= $data['pedido']['ventana_entrega'] ?></p>
                    <p class="mb-1"><strong>Tipo:</strong> <?= $data['pedido']['tipo_entrega'] ?></p>
                    
                    <?php if($data['pedido']['tipo_entrega'] == 'DOMICILIO'): ?>
                        <div class="alert alert-light border mt-2">
                            <i class="fas fa-map-marker-alt text-danger"></i> 
                            <?= $data['pedido']['calle'] ?> #<?= $data['pedido']['numero'] ?><br>
                            <small class="text-muted"><?= $data['pedido']['distrito_nombre'] ?> (Ref: <?= $data['pedido']['referencia'] ?>)</small>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-light border mt-2">
                            <i class="fas fa-store text-warning"></i> Recojo en Tienda Natividad
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Productos -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-box-open me-2"></i>Contenido del Pedido
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">P. Unitario</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['detalles'] as $item): ?>
                        <tr>
                            <td>
                                <?php if($item['foto']): ?>
                                    <img src="<?= BASE_URL ?>public/img/<?= $item['foto'] ?>" width="40" class="rounded me-2">
                                <?php endif; ?>
                                <?= $item['nombre'] ?>
                            </td>
                            <td class="text-center fw-bold"><?= $item['cantidad'] ?></td>
                            <td class="text-end">S/ <?= number_format($item['precio_unitario_congelado'], 3) ?></td>
                            <td class="text-end">S/ <?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-white">
                        <tr>
                            <td colspan="3" class="text-end">Subtotal Productos:</td>
                            <td class="text-end">S/ <?= number_format($data['pedido']['subtotal_productos'], 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Costo Envío:</td>
                            <td class="text-end">S/ <?= number_format($data['pedido']['costo_envio'], 2) ?></td>
                        </tr>
                        <tr class="fs-5 table-active border-top border-dark">
                            <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                            <td class="text-end text-success fw-bold">S/ <?= number_format($data['pedido']['costo_total'], 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR PEDIDO -->
<div class="modal fade" id="modalEditarPedido" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="<?= BASE_URL ?>admin/actualizarPedido">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Administrar Pedido</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pedido" value="<?= $data['pedido']['id_pedido'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Estado del Pedido</label>
                        <select name="estado" class="form-select" required>
                            <?php 
                                $estados = ['PENDIENTE_PAGO', 'PAGADO', 'EN_PREPARACION', 'LISTO_PARA_RECOJO', 'EN_CAMINO', 'ENTREGADO', 'CANCELADO'];
                                foreach($estados as $e): 
                            ?>
                                <option value="<?= $e ?>" <?= $data['pedido']['estado'] == $e ? 'selected' : '' ?>>
                                    <?= $e ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Entrega</label>
                            <input type="date" name="fecha_entrega" class="form-control" value="<?= $data['pedido']['fecha_entrega'] ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Turno</label>
                            <select name="ventana_entrega" class="form-select">
                                <option value="MAÑANA" <?= $data['pedido']['ventana_entrega'] == 'MAÑANA' ? 'selected' : '' ?>>
                                    Mañana (5:00 - 8:00 AM)
                                </option>
                                <option value="TARDE" <?= $data['pedido']['ventana_entrega'] == 'TARDE' ? 'selected' : '' ?>>
                                    Tarde (5:00 - 8:00 PM)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>