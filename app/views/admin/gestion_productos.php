<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bread-slice me-2"></i>Inventario de Productos</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalProducto" onclick="limpiarModal()">
            <i class="fas fa-plus me-2"></i>Nuevo Producto
        </button>
    </div>

    <?php if (isset($data['success'])): ?>
        <div class="alert alert-success"><?= $data['success'] ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark" style="color:#000">
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Regla B2B</th>
                    <th>Mínimo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['productos'] as $p): ?>
                <tr>
                    <td>
                        <?php if ($p['foto']): ?>
                            <img src="<?= BASE_URL ?>public/img/<?= $p['foto'] ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                        <?php else: ?>
                            <span class="text-muted"><i class="fas fa-image"></i></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $p['nombre'] ?></td>
                    <td><?= $p['categoria_nombre'] ?></td>
                    <td>
                        <?php if($p['unidades_base_b2b'] > 0): ?>
                            <?= $p['unidades_base_b2b'] ?>u = S/ <?= $p['soles_base_b2b'] ?>
                        <?php else: ?>
                            Precio Unit: S/ <?= $p['precio_b2c'] ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $p['unidad_minima_b2b'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick='editarProducto(<?= json_encode($p) ?>)'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="<?= BASE_URL ?>admin/eliminarProducto/<?= $p['id_producto'] ?>" 
                           class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CREAR/EDITAR -->
<div class="modal fade" id="modalProducto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="accion" id="accion" value="crear">
                    <input type="hidden" name="id_producto" id="id_producto">
                    <input type="hidden" name="foto_actual" id="foto_actual">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Categoría</label>
                            <select name="id_categoria" id="id_categoria" class="form-select" required>
                                <?php foreach($data['categorias'] as $c): ?>
                                    <option value="<?= $c['id_categoria'] ?>"><?= $c['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row p-3 bg-light border rounded mx-1 mb-3">
                        <h6 class="text-primary">Configuración de Precios B2B</h6>
                        <div class="col-md-4 mb-3">
                            <label>Unidades Base (Ej: 6)</label>
                            <input type="number" name="unidades_base_b2b" id="unidades_base_b2b" class="form-control" value="0">
                            <small class="text-muted">Pon 0 si es precio unitario simple</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Precio por Base (Ej: 1.00)</label>
                            <input type="number" step="0.01" name="soles_base_b2b" id="soles_base_b2b" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Precio Unitario (Fallback)</label>
                            <input type="number" step="0.01" name="precio_b2c" id="precio_b2c" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Venta Mínima (Unidades)</label>
                            <input type="number" name="unidad_minima_b2b" id="unidad_minima_b2b" class="form-control" value="1">
                        </div>
                        <div class="col-md-6 d-flex align-items-center mt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="disponible_b2b" id="disponible_b2b" checked>
                                <label class="form-check-label">Disponible para Venta</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Imagen del Producto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function limpiarModal() {
    document.getElementById('modalTitulo').innerText = 'Nuevo Producto';
    document.getElementById('accion').value = 'crear';
    document.getElementById('id_producto').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('precio_b2c').value = '';
    document.getElementById('unidades_base_b2b').value = '0';
    document.getElementById('soles_base_b2b').value = '0';
    document.getElementById('unidad_minima_b2b').value = '1';
}

function editarProducto(p) {
    var myModal = new bootstrap.Modal(document.getElementById('modalProducto'));
    document.getElementById('modalTitulo').innerText = 'Editar Producto';
    document.getElementById('accion').value = 'editar';
    document.getElementById('id_producto').value = p.id_producto;
    document.getElementById('foto_actual').value = p.foto;
    
    document.getElementById('nombre').value = p.nombre;
    document.getElementById('descripcion').value = p.descripcion;
    document.getElementById('id_categoria').value = p.id_categoria;
    document.getElementById('precio_b2c').value = p.precio_b2c;
    document.getElementById('unidades_base_b2b').value = p.unidades_base_b2b;
    document.getElementById('soles_base_b2b').value = p.soles_base_b2b;
    document.getElementById('unidad_minima_b2b').value = p.unidad_minima_b2b;
    document.getElementById('disponible_b2b').checked = (p.disponible_b2b == 1);
    
    myModal.show();
}
</script>

<?php include '../app/views/layouts/footer.php'; ?>