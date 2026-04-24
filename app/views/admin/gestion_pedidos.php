<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-receipt me-2"></i>Gestión de Pedidos</h2>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Fecha de Entrega</label>
                    <input type="date" name="fecha" class="form-control" value="<?= $_GET['fecha'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="PENDIENTE_PAGO" <?= ($_GET['estado'] ?? '') == 'PENDIENTE_PAGO' ? 'selected' : '' ?>>Pendiente Pago</option>
                        <option value="PAGADO" <?= ($_GET['estado'] ?? '') == 'PAGADO' ? 'selected' : '' ?>>Pagado / Confirmado</option>
                        <option value="EN_PREPARACION" <?= ($_GET['estado'] ?? '') == 'EN_PREPARACION' ? 'selected' : '' ?>>En Producción</option>
                        <option value="ENTREGADO" <?= ($_GET['estado'] ?? '') == 'ENTREGADO' ? 'selected' : '' ?>>Entregado</option>
                        <option value="CANCELADO" <?= ($_GET['estado'] ?? '') == 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= BASE_URL ?>admin/gestionPedidos" class="btn btn-outline-secondary w-100">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Pedidos -->
    <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm bg-white rounded">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Entrega</th>
                    <th>Total</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['pedidos'])): ?>
                    <tr><td colspan="7" class="text-center py-4">No se encontraron pedidos.</td></tr>
                <?php else: ?>
                    <?php foreach($data['pedidos'] as $p): ?>
                    <tr>
                        <td class="fw-bold">#<?= str_pad($p['id_pedido'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <?= $p['razon_social'] ?: ($p['cliente_nombre'] . ' ' . $p['cliente_apellidos']) ?><br>
                            <small class="text-muted"><?= $p['tipo_comprobante'] ?></small>
                        </td>
                        <td>
                            <?= date('d/m', strtotime($p['fecha_entrega'])) ?>
                            <span class="badge bg-light text-dark border"><?= $p['ventana_entrega'] ?></span>
                            <div class="small text-muted"><?= $p['tipo_entrega'] == 'DOMICILIO' ? 'Delivery' : 'Recojo' ?></div>
                        </td>
                        <td class="fw-bold text-success">S/ <?= number_format($p['costo_total'], 2) ?></td>
                        <td><?= $p['tipo_entrega'] ?></td>
                        <td>
                            <?php 
                                $bg = 'bg-secondary';
                                if($p['estado'] == 'PENDIENTE_PAGO') $bg = 'bg-warning text-dark';
                                if($p['estado'] == 'PAGADO') $bg = 'bg-info text-dark';
                                if($p['estado'] == 'ENTREGADO') $bg = 'bg-success';
                                if($p['estado'] == 'CANCELADO') $bg = 'bg-danger';
                            ?>
                            <span class="badge <?= $bg ?>"><?= $p['estado'] ?></span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>admin/detallePedido/<?= $p['id_pedido'] ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-dark" 
                                        onclick="abrirModalEstado(<?= $p['id_pedido'] ?>, '<?= $p['estado'] ?>')" title="Cambiar Estado">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Cambiar Estado -->
<div class="modal fade" id="modalEstado" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="<?= BASE_URL ?>admin/cambiarEstadoPedido">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar Estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pedido" id="modal_id_pedido">
                    <label class="form-label">Nuevo Estado:</label>
                    <select name="estado" id="modal_select_estado" class="form-select">
                        <option value="PENDIENTE_PAGO">PENDIENTE DE PAGO</option>
                        <option value="PAGADO">PAGADO / CONFIRMADO</option>
                        <option value="EN_PREPARACION">EN PRODUCCIÓN</option>
                        <option value="LISTO_PARA_RECOJO">LISTO PARA RECOJO</option>
                        <option value="EN_CAMINO">EN CAMINO (Delivery)</option>
                        <option value="ENTREGADO">ENTREGADO (Finalizado)</option>
                        <option value="CANCELADO">CANCELADO</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalEstado(id, estadoActual) {
    document.getElementById('modal_id_pedido').value = id;
    document.getElementById('modal_select_estado').value = estadoActual;
    new bootstrap.Modal(document.getElementById('modalEstado')).show();
}
</script>

<?php include '../app/views/layouts/footer.php'; ?>