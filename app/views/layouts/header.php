<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>public/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="fas fa-bread-slice me-2"></i><?php echo APP_NAME; ?>
            </a>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>
                        <?php echo $_SESSION['usuario_nombre']; ?>
                        <span class="badge bg-secondary ms-1"><?php echo $_SESSION['usuario_rol']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>cliente/carrito">
                            <i class="fas fa-shopping-cart me-2"></i>Mi Carrito
                        </a></li>
                        <?php if ($_SESSION['usuario_rol'] != 'CLIENTE_ESTANDAR'): ?>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>cliente/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard B2B
                        </a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin">
                            <i class="fas fa-cog me-2"></i>Administración
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $data['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($data['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $data['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>