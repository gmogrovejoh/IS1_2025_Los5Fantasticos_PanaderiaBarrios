<?php 
$titulo = 'Gestión de Clientes - Panadería Barrios';
include '../app/views/layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Gestión de Clientes</h2>
    <a href="<?= BASE_URL ?>admin" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lista de Clientes Registrados</h5>
    </div>
    <div class="card-body">
        <?php if (empty($data['clientes'])): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                No hay clientes registrados en el sistema.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol Actual</th>
                            <th>RUC</th>
                            <th>Razón Social</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['clientes'] as $cliente): ?>
                            <tr>
                                <td><?= $cliente['id_cliente'] ?></td>
                                <td>
                                    <strong><?= $cliente['nombre'] ?> <?= $cliente['apellidos'] ?></strong>
                                </td>
                                <td><?= $cliente['email'] ?></td>
                                <td><?= $cliente['telefono'] ?: '-' ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $cliente['rol'] == 'CLIENTE_ESTANDAR' ? 'primary' : 
                                            ($cliente['rol'] == 'MAYORISTA_BOLETA' ? 'success' : 'warning'); 
                                    ?>">
                                        <?= str_replace('_', ' ', $cliente['rol']) ?>
                                    </span>
                                </td>
                                <td><?= $cliente['ruc'] ?: '-' ?></td>
                                <td><?= $cliente['razon_social'] ?: '-' ?></td>
                                <td><?= date('d/m/Y', strtotime($cliente['fecha_registro'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarCliente<?= $cliente['id_cliente'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modales para editar clientes -->
<?php foreach ($data['clientes'] as $cliente): ?>
<div class="modal fade" id="modalEditarCliente<?= $cliente['id_cliente'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>
                    Editar Cliente: <?= $cliente['nombre'] ?> <?= $cliente['apellidos'] ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <!-- Cambiar Rol -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Cambiar Rol de Usuario</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= BASE_URL ?>admin/gestionClientes">
                                    <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Rol Actual: 
                                            <span class="badge bg-secondary"><?= str_replace('_', ' ', $cliente['rol']) ?></span>
                                        </label>
                                        <select name="rol" class="form-control" required>
                                            <option value="CLIENTE_ESTANDAR" <?= $cliente['rol'] == 'CLIENTE_ESTANDAR' ? 'selected' : '' ?>>
                                                Cliente Estándar (B2C)
                                            </option>
                                            <option value="MAYORISTA_BOLETA" <?= $cliente['rol'] == 'MAYORISTA_BOLETA' ? 'selected' : '' ?>>
                                                Mayorista con Boleta (B2B)
                                            </option>
                                            <option value="EMPRESA_FACTURA" <?= $cliente['rol'] == 'EMPRESA_FACTURA' ? 'selected' : '' ?>>
                                                Empresa con Factura (B2B)
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" name="actualizar_rol" class="btn btn-primary w-100">
                                        <i class="fas fa-user-tag me-2"></i>Actualizar Rol
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Datos de Empresa -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Datos Empresariales</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= BASE_URL ?>admin/gestionClientes">
                                    <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">RUC</label>
                                        <input type="text" name="ruc" class="form-control" 
                                               value="<?= $cliente['ruc'] ?>" maxlength="11"
                                               placeholder="20123456789">
                                        <small class="form-text text-muted">11 dígitos para empresas</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Razón Social</label>
                                        <input type="text" name="razon_social" class="form-control" 
                                               value="<?= $cliente['razon_social'] ?>"
                                               placeholder="Nombre de la empresa">
                                    </div>
                                    
                                    <button type="submit" name="actualizar_empresa" class="btn btn-success w-100">
                                        <i class="fas fa-building me-2"></i>Actualizar Empresa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información adicional -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Información del Cliente</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Email:</strong> <?= $cliente['email'] ?></p>
                                    <p class="mb-1"><strong>Teléfono:</strong> <?= $cliente['telefono'] ?: 'No registrado' ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Fecha de registro:</strong> <?= date('d/m/Y H:i', strtotime($cliente['fecha_registro'])) ?></p>
                                    <p class="mb-0"><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Información sobre roles -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-light">
            <h6><i class="fas fa-question-circle me-2"></i>Información sobre Roles de Usuario</h6>
            <div class="row">
                <div class="col-md-4">
                    <strong>Cliente Estándar (B2C):</strong>
                    <ul class="mb-0">
                        <li>Acceso al catálogo B2C (packs, pastelería, especiales)</li>
                        <li>Solo recojo en tienda</li>
                        <li>Comprobante: Boleta</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <strong>Mayorista con Boleta (B2B):</strong>
                    <ul class="mb-0">
                        <li>Acceso completo al catálogo B2B</li>
                        <li>Pedidos por monto entero</li>
                        <li>Delivery disponible</li>
                        <li>Comprobante: Boleta</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <strong>Empresa con Factura (B2B):</strong>
                    <ul class="mb-0">
                        <li>Todas las funciones de Mayorista</li>
                        <li>Comprobante: Factura</li>
                        <li>Requiere RUC y Razón Social</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>