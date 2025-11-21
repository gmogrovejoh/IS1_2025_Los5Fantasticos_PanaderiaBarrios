<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-cog me-2"></i>Panel de Administración</h2>
        <p class="text-muted">Gestión completa del sistema de panadería</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h4>Gestión de Clientes</h4>
                <p class="text-muted">Administrar usuarios, roles y datos empresariales</p>
                <a href="<?php echo BASE_URL; ?>admin/gestionClientes" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Gestionar Clientes
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-box fa-3x text-success mb-3"></i>
                <h4>Gestión de Productos</h4>
                <p class="text-muted">CRUD completo de productos y precios</p>
                <a href="<?php echo BASE_URL; ?>admin/gestionProductos" class="btn btn-success">
                    <i class="fas fa-box me-2"></i>Gestionar Productos
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-clipboard-list fa-3x text-warning mb-3"></i>
                <h4>Hoja de Producción</h4>
                <p class="text-muted">Calcular producción necesaria por fecha</p>
                <a href="<?php echo BASE_URL; ?>admin/hojaProduccion" class="btn btn-warning">
                    <i class="fas fa-clipboard-list me-2"></i>Ver Producción
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar me-2"></i>Estadísticas Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="text-primary">0</h3>
                        <small>Pedidos Hoy</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success">S/ 0.00</h3>
                        <small>Ventas Hoy</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-clock me-2"></i>Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                    <i class="fas fa-plus me-1"></i>Nuevo Producto
                </a>
                <a href="#" class="btn btn-outline-success btn-sm me-2 mb-2">
                    <i class="fas fa-user-plus me-1"></i>Nuevo Cliente
                </a>
                <a href="#" class="btn btn-outline-info btn-sm mb-2">
                    <i class="fas fa-file-export me-1"></i>Exportar Datos
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>