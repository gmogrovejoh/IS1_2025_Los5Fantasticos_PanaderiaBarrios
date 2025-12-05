<?php include '../app/views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i>
                        Cambiar Rol de Usuario
                    </h4>
                </div>

                <div class="card-body">

                    <form action="<?php echo BASE_URL; ?>admin/actualizarRol" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">ID del Usuario</label>
                            <input 
                                type="number" 
                                name="id_cliente" 
                                class="form-control form-control-lg shadow-sm"
                                placeholder="Ej: 12"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nuevo Rol</label>
                            <select 
                                name="rol" 
                                class="form-select form-select-lg shadow-sm"
                                required
                            >
                                <option value="" disabled selected>Seleccione un rol...</option>
                                <option value="CLIENTE_ESTANDAR">Cliente Estándar</option>
                                <option value="MAYORISTA_BOLETA">Mayorista (Boleta)</option>
                                <option value="EMPRESA_FACTURA">Empresa (Factura)</option>
                            </select>
                        </div>

                        <button 
                            type="submit" 
                            class="btn btn-primary w-100 btn-lg shadow-sm"
                        >
                            <i class="fas fa-save me-2"></i>
                            Actualizar Rol
                        </button>
                    </form>

                </div>
            </div>

            <!-- Botón volver -->
            <div class="text-center mt-3">
                <a href="<?php echo BASE_URL; ?>admin/gestionClientes" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver
                </a>
            </div>

        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>
