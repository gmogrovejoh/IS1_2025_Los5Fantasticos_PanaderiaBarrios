<?php include '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-box me-2"></i>Gestión de Productos</h2>
    <a href="<?php echo BASE_URL; ?>admin" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
    </a>
</div>

<?php if (isset($data['success'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle me-2"></i><?php echo $data['success']; ?>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Producto</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden">
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <!-- <div class="mb-3">
                        <label class="form-label">Foto (nombre de archivo)</label>
                        <input type="text" name="foto" class="form-control" placeholder="ej: /images/Bread.jpg">
                        <small class="text-muted">Coloca la imagen en public/img/</small>
                    </div>
                    --> 
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio B2C (S/)</label>
                            <input type="number" step="0.01" name="precio_b2c" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="id_categoria" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="1">Panes Salados</option>
                                <option value="2">Panes Dulces</option>
                                <option value="3">Panes Integrales</option>
                                <option value="4">Especiales de Temporada</option>
                                <option value="5">Pastelería y Repostería</option>
                                <option value="6">Packs y Ofertas</option>
                            </select>
                        </div>
                    </div>
                    <div class="border rounded p-3 mb-3">
                        <h6 class="mb-2"><i class="fas fa-industry me-2"></i>Reglas B2B</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Unid. base</label>
                                <input type="number" name="unidades_base_b2b" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Soles base</label>
                                <input type="number" step="0.01" name="soles_base_b2b" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mínimo (empanadas)</label>
                                <input type="number" name="unidad_minima_b2b" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="disponible_b2c" id="disp_b2c" checked>
                            <label class="form-check-label" for="disp_b2c">Disponible B2C</label>
                        </div>
                        <div class="form-check form-check-inline ms-3">
                            <input class="form-check-input" type="checkbox" name="disponible_b2b" id="disp_b2b" checked>
                            <label class="form-check-label" for="disp_b2b">Disponible B2B</label>
                        </div>
                    </div>
                    <button type="submit" name="crear_producto" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Crear Producto
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Productos Existentes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($data['productos'])): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No hay productos registrados.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio B2C</th>
                                    <th>Regla B2B</th>
                                    <th>Canales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['productos'] as $p): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $p['nombre']; ?></strong><br>
                                        <small class="text-muted"><?php echo $p['descripcion']; ?></small>
                                    </td>
                                    <td><?php echo $p['categoria_nombre']; ?></td>
                                    <td>S/ <?php echo number_format($p['precio_b2c'], 2); ?></td>
                                    <td>
                                        <small>
                                            <?php 
                                                if ($p['soles_base_b2b'] && $p['unidades_base_b2b']) {
                                                    echo "{$p['unidades_base_b2b']} unid = S/ " . number_format($p['soles_base_b2b'], 2);
                                                } else {
                                                    echo '-';
                                                }
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $p['disponible_b2c'] ? 'primary' : 'secondary'; ?>">B2C</span>
                                        <span class="badge bg-<?php echo $p['disponible_b2b'] ? 'success' : 'secondary'; ?>">B2B</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>