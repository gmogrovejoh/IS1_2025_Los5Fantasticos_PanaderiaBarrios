<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-circle me-2"></i>Mi Perfil</h2>
        <span class="badge bg-primary fs-6"><?= str_replace('_', ' ', $_SESSION['usuario_rol']) ?></span>
    </div>

    <!-- Mensajes de feedback -->
    <?php if (isset($data['mensaje']['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i><?= $data['mensaje']['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- COLUMNA IZQUIERDA: DATOS DE EMPRESA -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Información de Cuenta</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>cliente/perfil">
                        <input type="hidden" name="actualizar_perfil" value="1">
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="<?= $data['cliente']['nombre'] ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" value="<?= $data['cliente']['apellidos'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email (No editable)</label>
                            <input type="email" class="form-control bg-light" value="<?= $data['cliente']['email'] ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?= $data['cliente']['telefono'] ?>">
                        </div>

                        <hr>
                        <h6 class="text-muted mb-3">Datos de Facturación</h6>

                        <div class="mb-3">
                            <label class="form-label">Razón Social</label>
                            <input type="text" name="razon_social" class="form-control" value="<?= $data['cliente']['razon_social'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RUC</label>
                            <input type="text" name="ruc" class="form-control" value="<?= $data['cliente']['ruc'] ?>" maxlength="11" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>

            
        </div>

        

        <!-- COLUMNA DERECHA: DIRECCIONES -->
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Mis Direcciones</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevaDireccion">
                        <i class="fas fa-plus me-1"></i> Nueva
                    </button>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($data['direcciones'])): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-map-signs fa-3x mb-3"></i>
                            <p>No tienes direcciones registradas.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($data['direcciones'] as $dir): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= $dir['alias'] ?></strong>
                                        <span class="badge bg-light text-dark ms-2"><?= $dir['distrito_nombre'] ?></span>
                                        <p class="mb-0 text-muted small">
                                            <?= $dir['calle'] ?> #<?= $dir['numero'] ?>
                                            <?= $dir['referencia'] ? '<br>Ref: ' . $dir['referencia'] : '' ?>
                                        </p>
                                    </div>
                                    <form method="POST" action="<?= BASE_URL ?>cliente/eliminarDireccion" onsubmit="return confirm('¿Eliminar esta dirección?');">
                                        <input type="hidden" name="id_direccion" value="<?= $dir['id_direccion'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- TARJETA 2: SEGURIDAD (NUEVO) -->
            <div class="card shadow-sm h-100 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Seguridad</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>cliente/actualizarPassword">
                        <div class="mb-3">
                            <label class="form-label">Contraseña Actual</label>
                            <input type="password" name="clave_actual" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="clave_nueva" class="form-control" minlength="6" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Nueva</label>
                            <input type="password" name="clave_confirmar" class="form-control" minlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">
                            <i class="fas fa-key me-2"></i>Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>
    </div>
</div>

<!-- MODAL NUEVA DIRECCIÓN -->
<div class="modal fade" id="modalNuevaDireccion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nueva Dirección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>cliente/guardarDireccion">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alias (Ej: Tienda Principal, Almacén)</label>
                        <input type="text" name="alias" class="form-control" required placeholder="Mi Local">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Distrito</label>
                        <select name="id_distrito" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($data['distritos'] as $distrito): ?>
                                <option value="<?= $distrito['id_distrito'] ?>">
                                    <?= $distrito['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3">
                                <label class="form-label">Calle / Av.</label>
                                <input type="text" name="calle" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Número</label>
                                <input type="text" name="numero" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" name="referencia" class="form-control" placeholder="Frente al parque...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Dirección</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>