<?php include '../app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Registro de Usuario</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>auth/registro">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ruc" class="form-label">RUC *</label>
                        <input type="text" class="form-control" id="ruc" name="ruc" maxlength="11" required placeholder="Solo números">
                    </div>

                    <div class="mb-3">
                        <label for="razon_social" class="form-label">Razón Social *</label>
                        <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contrasenia" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-user-plus me-2"></i>Registrarse
                    </button>
                </form>
                
                <hr>
                <div class="text-center">
                    <p>¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>auth/login">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>