<?php 
$titulo = 'Panel de Administración - Panadería Barrios';
include '../app/views/layouts/header.php'; 
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-cog me-2"></i>Panel de Administración
            <small class="text-muted">Gestión completa del sistema</small>
        </h2>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card text">
            <div class="card-body text-center">
                <i class="fas fa-users stats-icon"></i>
                <h3>12</h3>
                <p>Clientes Registrados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card text">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart stats-icon"></i>
                <h3>0</h3>
                <p>Pedidos Hoy</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card text">
            <div class="card-body text-center">
                <i class="fas fa-box stats-icon"></i>
                <h3>14</h3>
                <p>Productos Activos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card text">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign stats-icon"></i>
                <h3>S/ 115</h3>
                <p>Ventas del Mes</p>
            </div>
        </div>
    </div>
</div>

<!-- Módulos principales -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm dashboard-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-users fa-4x text-primary"></i>
                </div>
                <h4 class="card-title">Gestión de Clientes</h4>
                <p class="card-text">
                    Administrar usuarios, cambiar roles entre Cliente Estándar, 
                    Mayorista y Empresa. Gestionar datos empresariales.
                </p>
                <a href="<?= BASE_URL ?>admin/gestionClientes" class="btn btn-primary btn-lg">
                    <i class="fas fa-users me-2"></i>Gestionar Clientes
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm dashboard-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-box fa-4x text-success"></i>
                </div>
                <h4 class="card-title">Gestión de Productos</h4>
                <p class="card-text">
                    CRUD completo de productos, configurar precios B2C y B2B, 
                    reglas de venta, disponibilidad por canal.
                </p>
                <a href="<?= BASE_URL ?>admin/gestionProductos" class="btn btn-success btn-lg">
                    <i class="fas fa-box me-2"></i>Gestionar Productos
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm dashboard-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-clipboard-list fa-4x text-warning"></i>
                </div>
                <h4 class="card-title">Hoja de Producción</h4>
                <p class="card-text">
                    Calcular producción necesaria por fecha y ventana. 
                    Descomposición automática de packs en componentes.
                </p>
                <a href="<?= BASE_URL ?>admin/hojaProduccion" class="btn btn-warning btn-lg">
                    <i class="fas fa-clipboard-list me-2"></i>Ver Producción
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Módulos secundarios -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line me-2"></i>Reportes y Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <button class="btn btn-outline-info w-100 mb-2" disabled>
                            <i class="fas fa-chart-bar me-2"></i>Ventas por Período
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-info w-100 mb-2" disabled>
                            <i class="fas fa-chart-pie me-2"></i>Productos Más Vendidos
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-info w-100" disabled>
                            <i class="fas fa-users me-2"></i>Clientes Frecuentes
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-info w-100" disabled>
                            <i class="fas fa-map-marker-alt me-2"></i>Zonas de Entrega
                        </button>
                    </div>
                </div>
                <small class="text-muted">Funcionalidades en desarrollo</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-cog me-2"></i>Configuración del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clock me-2"></i>Horarios de Corte</span>
                        <button class="btn btn-sm btn-outline-secondary" disabled>Configurar</button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-truck me-2"></i>Zonas de Envío</span>
                        <button class="btn btn-sm btn-outline-secondary" disabled>Gestionar</button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-store me-2"></i>Sedes</span>
                        <button class="btn btn-sm btn-outline-secondary" disabled>Administrar</button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-tags me-2"></i>Categorías</span>
                        <button class="btn btn-sm btn-outline-secondary" disabled>Editar</button>
                    </div>
                </div>
                <small class="text-muted">Funcionalidades en desarrollo</small>
            </div>
        </div>
    </div>
</div>


<?php include '../app/views/layouts/footer.php'; ?>