<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-users me-2"></i>Gestión de Clientes</h2>
        <!-- BOTÓN NUEVO CLIENTE -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
            <i class="fas fa-user-plus me-2"></i>Nuevo Cliente
        </button>
    </div>

    <!-- Mensajes -->
    <?php if (isset($data['mensaje']['success'])): ?>
        <div class="alert alert-success"><?= $data['mensaje']['success'] ?></div>
    <?php endif; ?>
    <?php if (isset($data['mensaje']['error'])): ?>
        <div class="alert alert-danger"><?= $data['mensaje']['error'] ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>ID</th>
                            <th>Empresa / Cliente</th>
                            <th>Contacto</th>
                            <th>Rol</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['clientes'] as $c): ?>
                        <tr>
                            <td><?= $c['id_cliente'] ?></td>
                            <td>
                                <strong><?= $c['razon_social'] ?: 'N/A' ?></strong><br>
                                <small><?= $c['nombre'] ?> <?= $c['apellidos'] ?></small>
                                <?php if($c['ruc']): ?>
                                    <span class="badge bg-light text-dark border ms-1"><?= $c['ruc'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $c['telefono'] ?><br><small class="text-muted"><?= $c['email'] ?></small></td>
                            <td><span class="badge bg-info text-dark"><?= $c['rol'] ?></span></td>
                            <td class="text-end">
                                <!-- BOTÓN GESTIONAR DIRECCIONES -->
                                <button class="btn btn-sm btn-outline-dark me-1" 
                                        onclick="abrirModalDirecciones(<?= $c['id_cliente'] ?>, '<?= $c['nombre'] ?>')"
                                        title="Gestionar Direcciones">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>

                                <!-- BOTÓN EDITAR -->
                                <button class="btn btn-sm btn-outline-primary me-1" 
                                        onclick='editarCliente(<?= json_encode($c) ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- BOTÓN ELIMINAR -->
                                <?php if($c['rol'] != 'ADMIN'): ?>
                                <a href="<?= BASE_URL ?>admin/gestionClientes?eliminar=<?= $c['id_cliente'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('¿Borrar cliente y todo su historial?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 1: NUEVO CLIENTE -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="<?= BASE_URL ?>admin/gestionClientes">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Registrar Nuevo Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="accion" value="crear_cliente">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Apellidos *</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Razón Social (Empresas)</label>
                            <input type="text" name="razon_social" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>RUC</label>
                            <input type="text" name="ruc" class="form-control" maxlength="11">
                        </div>
                        <div class="col-12">
                            <label>Rol</label>
                            <select name="rol" class="form-select">
                                <option value="EMPRESA_FACTURA">EMPRESA (Factura)</option>
                                <option value="MAYORISTA_BOLETA">MAYORISTA (Boleta)</option>
                            </select>
                            <small class="text-muted">La contraseña por defecto será: <strong>123456</strong></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: GESTIONAR DIRECCIONES -->
<div class="modal fade" id="modalDirecciones" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Direcciones de <span id="lbl_nombre_cliente"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- LISTA EXISTENTE -->
                <h6 class="text-muted">Direcciones actuales:</h6>
                <ul class="list-group mb-3" id="lista_direcciones">
                    <li class="list-group-item text-center">Cargando...</li>
                </ul>

                <hr>
                
                <!-- FORMULARIO NUEVA DIRECCIÓN -->
                <h6 class="text-primary"><i class="fas fa-plus-circle me-1"></i> Agregar Nueva Dirección</h6>
                <form method="POST" action="<?= BASE_URL ?>admin/gestionClientes">
                    <input type="hidden" name="accion" value="nueva_direccion_admin">
                    <input type="hidden" name="id_cliente_dir" id="id_cliente_dir">
                    
                    <div class="mb-2">
                        <input type="text" name="alias" class="form-control form-control-sm" placeholder="Alias (Ej: Tienda Centro)" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-8">
                            <input type="text" name="calle" class="form-control form-control-sm" placeholder="Calle / Av." required>
                        </div>
                        <div class="col-4">
                            <input type="text" name="numero" class="form-control form-control-sm" placeholder="Número" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <select name="id_distrito" class="form-select form-select-sm" required>
                            <option value="">-- Distrito --</option>
                            <?php foreach($data['distritos'] as $d): ?>
                                <option value="<?= $d['id_distrito'] ?>"><?= $d['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="referencia" class="form-control form-control-sm" placeholder="Referencia">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">Guardar Dirección</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts (Mantenemos el de editar que ya tenías y agregamos el de direcciones) -->
<script>
// ... tu función editarCliente existente ...

const BASE_URL = '<?= BASE_URL ?>';

function abrirModalDirecciones(idCliente, nombre) {
    document.getElementById('lbl_nombre_cliente').textContent = nombre;
    document.getElementById('id_cliente_dir').value = idCliente;
    
    // Cargar direcciones vía AJAX
    const lista = document.getElementById('lista_direcciones');
    lista.innerHTML = '<li class="list-group-item text-center">Cargando...</li>';
    
    fetch(BASE_URL + 'admin/apiDirecciones/' + idCliente)
        .then(res => res.json())
        .then(data => {
            lista.innerHTML = '';
            if(data.length > 0) {
                data.forEach(d => {
                    lista.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${d.alias}</strong><br>
                                <small>${d.calle} #${d.numero} (${d.distrito_nombre})</small>
                            </div>
                            <span class="badge bg-light text-dark">ID: ${d.id_direccion}</span>
                        </li>
                    `;
                });
            } else {
                lista.innerHTML = '<li class="list-group-item text-muted text-center">Sin direcciones registradas</li>';
            }
        });

    new bootstrap.Modal(document.getElementById('modalDirecciones')).show();
}

// Función para editar (ya la tenías, asegúrate de incluirla también aquí)
function editarCliente(cliente) {
    // ... lógica para llenar modal de editar ...
    // (Usa el código que te di en la respuesta anterior para el modal #modalEditarCliente)
    // Solo para que no de error si no existe el modal en este snippet
    var modalEl = document.getElementById('modalEditarCliente');
    if(modalEl){
        document.getElementById('edit_id').value = cliente.id_cliente;
        document.getElementById('edit_nombre').value = cliente.nombre;
        document.getElementById('edit_apellidos').value = cliente.apellidos;
        document.getElementById('edit_email').value = cliente.email;
        document.getElementById('edit_telefono').value = cliente.telefono;
        document.getElementById('edit_ruc').value = cliente.ruc;
        document.getElementById('edit_razon_social').value = cliente.razon_social;
        document.getElementById('edit_rol').value = cliente.rol;
        new bootstrap.Modal(modalEl).show();
    }
}
</script>

<!-- Asegúrate de incluir el modalEditarCliente aquí abajo también (del paso anterior) -->
<!-- ... (Pegar código del modalEditarCliente de la respuesta previa) ... -->

<?php include '../app/views/layouts/footer.php'; ?>