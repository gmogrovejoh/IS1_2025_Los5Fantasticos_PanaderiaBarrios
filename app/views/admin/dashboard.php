<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-cogs me-2"></i>Panel de Administración</h2>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text display-4"><?= $data['stats']['productos'] ?></p>
                    <a href="<?= BASE_URL ?>admin/gestionProductos" class="text-white">Gestionar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text display-4"><?= $data['stats']['clientes'] ?></p>
                    <a href="<?= BASE_URL ?>admin/gestionClientes" class="text-white">Ver lista <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pedidos Activos</h5>
                    <p class="card-text display-4">Ver</p>
                    <a href="<?= BASE_URL ?>admin/gestionPedidos" class="text-white">Atender <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-list fa-3x text-secondary mb-3"></i>
                    <h4>Hoja de Producción</h4>
                    <p>Generar lista de panes a hornear para mañana.</p>
                    <a href="<?= BASE_URL ?>admin/hojaProduccion" class="btn btn-outline-dark">Ver Hoja</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-success">
                <div class="card-body text-center">
                    <i class="fas fa-cash-register fa-3x text-success mb-3"></i>
                    <h4>Caja / Nuevo Pedido</h4>
                    <p>Crear pedido manual para un cliente.</p>
                    <a href="<?= BASE_URL ?>admin/nuevoPedido" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i> Crear Pedido
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>