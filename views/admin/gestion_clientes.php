<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-users me-2"></i>Gestión de Clientes</h2>
        <p class="text-muted">Administrar usuarios, roles y datos empresariales</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Lista de Clientes Registrados</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
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
                        <td><?php echo $cliente['id_cliente']; ?></td>
                        <td><?php echo $cliente['nombre'] . ' ' . $cliente['apellidos']; ?></td>
                        <td><?php echo $cliente['email']; ?></td>
                        <td><?php echo $cliente['telefono'] ?: '-'; ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $cliente['rol'] == 'CLIENTE_ESTANDAR' ? 'primary' : 
                                    ($cliente['rol'] == 'MAYORISTA_BOLETA' ? 'success' : 'warning'); 
                            ?>">
                                <?php echo $cliente['rol']; ?>
                            </span>
                        </td>
                        <td><?php echo $cliente['ruc'] ?: '-'; ?></td>
                        <td><?php echo $cliente['razon_social'] ?: '-'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                    data-bs-target="#modalEditarCliente<?php echo $cliente['id_cliente']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Modal para editar cliente -->
                    <div class="modal fade" id="modalEditarCliente<?php echo $cliente['id_cliente']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Cliente: <?php echo $cliente['nombre']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Cambiar Rol</label>
                                            <select name="rol" class="form-control">
                                                <option value="CLIENTE_ESTANDAR" <?php echo $cliente['rol'] == 'CLIENTE_ESTANDAR' ? 'selected' : ''; ?>>
                                                    Cliente Estándar
                                                </option>
                                                <option value="MAYORISTA_BOLETA" <?php echo $cliente['rol'] == 'MAYORISTA_BOLETA' ? 'selected' : ''; ?>>
                                                    Mayorista (Boleta)
                                                </option>
                                                <option value="EMPRESA_FACTURA" <?php echo $cliente['rol'] == 'EMPRESA_FACTURA' ? 'selected' : ''; ?>>
                                                    Empresa (Factura)
                                                </option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">RUC</label>
                                            <input type="text" name="ruc" class="form-control" 
                                                   value="<?php echo $cliente['ruc']; ?>" maxlength="11">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Razón Social</label>
                                            <input type="text" name="razon_social" class="form-control" 
                                                   value="<?php echo $cliente['razon_social']; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="actualizar_rol" class="btn btn-primary">Actualizar Rol</button>
                                        <button type="submit" name="actualizar_empresa" class="btn btn-success">Actualizar Empresa</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>