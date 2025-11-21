<?php include '../app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>auth/login">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasenia" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </button>
                </form>
                
                <hr>
                <div class="text-center">
                    <p>¿No tienes cuenta? <a href="<?php echo BASE_URL; ?>auth/registro">Regístrate aquí</a></p>
                </div>
                
                <div class="mt-4">
                    <h6>Usuarios de prueba:</h6>
                    <small class="text-muted">
                        <strong>Cliente Estándar:</strong> nechuram@unjbg.edu.pe / 12345<br>
                        <strong>Mayorista:</strong> guillermo@gmail.com / 12345<br>
                        <strong>Empresa:</strong> albertix91@gmail.com / 12345
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>