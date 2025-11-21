<?php include '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-clipboard-list me-2"></i>Hoja de Producción</h2>
        <p class="text-muted">Cálculo de producción necesaria por fecha y ventana de entrega</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Filtros de Producción</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>admin/hojaProduccion">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Fecha de Entrega</label>
                    <input type="date" name="fecha" class="form-control" 
                           value="<?php echo $data['fecha_entrega']; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ventana de Entrega</label>
                    <select name="ventana" class="form-control">
                        <option value="MAÑANA" <?php echo $data['ventana_entrega'] == 'MAÑANA' ? 'selected' : ''; ?>>
                            Mañana (5:00 AM - 8:00 AM)
                        </option>
                        <option value="TARDE" <?php echo $data['ventana_entrega'] == 'TARDE' ? 'selected' : ''; ?>>
                            Tarde (5:00 PM - 8:00 PM)
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">
                        <i class="fas fa-search me-2"></i>Calcular Producción
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>
            Producción Requerida - <?php echo date('d/m/Y', strtotime($data['fecha_entrega'])); ?> 
            (<?php echo $data['ventana_entrega']; ?>)
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($data['produccion'])): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            No hay pedidos para la fecha y ventana seleccionada.
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-end">Cantidad Total Requerida</th>
                        <th class="text-end">Unidades por Kilo*</th>
                        <th class="text-end">Kilos Aproximados*</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_productos = 0;
                    foreach ($data['produccion'] as $producto => $cantidad): 
                        $total_productos += $cantidad;
                        // Estimación aproximada de unidades por kilo (esto debería venir de la BD)
                        $unidades_por_kilo = 20; // Valor por defecto
                        $kilos_aproximados = ceil($cantidad / $unidades_por_kilo);
                    ?>
                    <tr>
                        <td><strong><?php echo $producto; ?></strong></td>
                        <td class="text-end">
                            <span class="badge bg-primary fs-6"><?php echo $cantidad; ?> unidades</span>
                        </td>
                        <td class="text-end text-muted"><?php echo $unidades_por_kilo; ?></td>
                        <td class="text-end">
                            <strong><?php echo $kilos_aproximados; ?> kg</strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-warning">
                        <th>TOTAL PRODUCTOS</th>
                        <th class="text-end"><?php echo $total_productos; ?> unidades</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="alert alert-warning mt-3">
            <small>
                <strong>*Nota:</strong> Los valores de "Unidades por Kilo" y "Kilos Aproximados" son estimaciones. 
                Ajustar según las especificaciones reales de cada producto.
            </small>
        </div>
        
        <div class="mt-3">
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimir Hoja de Producción
            </button>
            <button class="btn btn-info">
                <i class="fas fa-file-excel me-2"></i>Exportar a Excel
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>