<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard B2B</h2>
        <p class="text-muted">Panel de control para clientes mayoristas</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                <h4>Pedido Rápido</h4>
                <p class="text-muted">Realiza pedidos por montos enteros de manera rápida y eficiente</p>
                <a href="<?php echo BASE_URL; ?>cliente/pedidoRapido" class="btn btn-primary">
                    <i class="fas fa-bolt me-2"></i>Hacer Pedido Rápido
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                <h4>Pedidos Programados</h4>
                <p class="text-muted">Configura pedidos recurrentes para tu negocio</p>
                <a href="#" class="btn btn-success">
                    <i class="fas fa-calendar-plus me-2"></i>Gestionar Programados
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-history fa-2x text-info mb-3"></i>
                <h5>Historial de Pedidos</h5>
                <a href="#" class="btn btn-info btn-sm">Ver Historial</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-2x text-warning mb-3"></i>
                <h5>Mi Carrito</h5>
                <a href="<?php echo BASE_URL; ?>cliente/carrito" class="btn btn-warning btn-sm">Ver Carrito</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-user fa-2x text-secondary mb-3"></i>
                <h5>Mi Perfil</h5>
                <a href="#" class="btn btn-secondary btn-sm">Editar Perfil</a>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <h5><i class="fas fa-info-circle me-2"></i>Información de tu cuenta</h5>
    <p><strong>Tipo de cliente:</strong> <?php echo $_SESSION['usuario_rol']; ?></p>
    <p><strong>Tipo de comprobante:</strong> <?php echo ($_SESSION['usuario_rol'] == 'EMPRESA_FACTURA') ? 'Factura' : 'Boleta'; ?></p>
</div>

<?php include '../app/views/layouts/footer.php'; ?>