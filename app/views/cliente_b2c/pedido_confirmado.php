<?php include '../app/views/layouts/header.php'; ?>

<div class="text-center mb-4">
    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
    <h2 class="text-success">¡Pedido Confirmado!</h2>
    <?php if (!empty($data['id_pedido'])): ?>
    <p class="lead">Tu pedido ha sido registrado exitosamente. ID: <strong>#<?php echo str_pad($data['id_pedido'], 6, '0', STR_PAD_LEFT); ?></strong></p>
    <?php else: ?>
    <p class="lead">Tu pedido ha sido registrado exitosamente.</p>
    <?php endif; ?>
</div>

<div class="text-center">
    <a href="<?php echo BASE_URL; ?>pedido/historial" class="btn btn-primary me-2">
        <i class="fas fa-history me-2"></i>Ver Mis Pedidos
    </a>
    <a href="<?php echo BASE_URL; ?>cliente/catalogo" class="btn btn-outline-primary">
        <i class="fas fa-store me-2"></i>Seguir Comprando
    </a>
</div>

<?php include '../app/views/layouts/footer.php'; ?>