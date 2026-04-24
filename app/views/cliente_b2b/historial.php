<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-receipt me-2"></i>Historial de Pedidos</h2>

    <?php if (empty($data['pedidos'])): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>No has realizado ningún pedido todavía.
            <a href="<?= BASE_URL ?>cliente/pedidoRapido" class="alert-link">Realizar un pedido</a>.
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Fecha Registro</th>
                                <th>Entrega</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['pedidos'] as $pedido): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold">#<?= str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_registro'])) ?></td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($pedido['fecha_entrega'])) ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= $pedido['ventana_entrega'] == 'MAÑANA' ? 'Mañana (5-8am)' : 'Tarde (5-8pm)' ?>
                                        </small>
                                    </td>
                                    <td class="fw-bold text-success">
                                        S/ <?= number_format($pedido['costo_total'], 2) ?>
                                    </td>
                                    <td>
                                        <?php
                                            $badge = 'bg-secondary';
                                            if ($pedido['estado'] == 'PENDIENTE_PAGO') $badge = 'bg-warning text-dark';
                                            if ($pedido['estado'] == 'PAGADO') $badge = 'bg-info text-dark';
                                            if ($pedido['estado'] == 'ENTREGADO') $badge = 'bg-success';
                                            if ($pedido['estado'] == 'CANCELADO') $badge = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= $pedido['estado'] ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>pedido/detalle/<?= $pedido['id_pedido'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Ver Items
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="<?= BASE_URL ?>cliente/pedidoRapido" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Pedido
        </a>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>