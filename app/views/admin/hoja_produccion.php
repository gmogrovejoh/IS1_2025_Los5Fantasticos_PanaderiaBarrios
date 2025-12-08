<?php 
$titulo = 'Hoja de Producción - Panadería Barrios';
include '../app/views/layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>Hoja de Producción</h2>
    <a href="<?= BASE_URL ?>admin" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros de Producción</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>admin/hojaProduccion" class="row g-3">
            <div class="col-md-4">
                <label for="fecha" class="form-label">Fecha de Entrega</label>
                <input type="date" name="fecha" id="fecha" class="form-control" 
                       value="<?= $fecha ?>" min="<?= date('Y-m-d') ?>" 
                       max="<?= date('Y-m-d', strtotime('+30 days')) ?>">
            </div>
            <div class="col-md-4">
                <label for="ventana" class="form-label">Ventana de Entrega</label>
                <select name="ventana" id="ventana" class="form-control">
                    <option value="MAÑANA" <?= $data['ventana_entrega'] == 'MAÑANA' ? 'selected' : '' ?>>
                        Mañana (5:00 AM - 8:00 AM)
                    </option>
                    <option value="TARDE" <?= $data['ventana_entrega'] == 'TARDE' ? 'selected' : '' ?>>
                        Tarde (5:00 PM - 8:00 PM)
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">
                    <i class="fas fa-search me-2"></i>Calcular Producción
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Resultados -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-factory me-2"></i>
            Producción Requerida - <?= date('d/m/Y', strtotime($data['fecha_entrega'])) ?> Turno: 
            (<?= $data['ventana_entrega'] === 'MAÑANA' ? 'Mañana' : 'Tarde' ?>)
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($produccion)): ?>
            <div class="alert alert-info text-center py-4">
                <i class="fas fa-info-circle fa-2x mb-3 text-muted"></i>
                <h5>No hay pedidos para producir</h5>
                <p class="mb-0">No se encontraron pedidos confirmados para la fecha y ventana seleccionada.</p>
            </div>
        <?php else: ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <h6><i class="fas fa-chart-bar me-2"></i>Resumen de Producción</h6>
                        <p class="mb-1"><strong>Total de productos diferentes:</strong> <?= count($produccion) ?></p>
                        <p class="mb-0"><strong>Cantidad total de unidades:</strong> 
                            <?= number_format(array_sum(array_column($produccion, 'cantidad_total'))) ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-clock me-2"></i>Horarios de Producción</h6>
                        <?php if ($ventana === 'MAÑANA'): ?>
                            <p class="mb-1"><strong>Inicio recomendado:</strong> 11:00 PM (día anterior)</p>
                            <p class="mb-0"><strong>Entrega lista:</strong> 4:30 AM</p>
                        <?php else: ?>
                            <p class="mb-1"><strong>Inicio recomendado:</strong> 7:00 AM</p>
                            <p class="mb-0"><strong>Entrega lista:</strong> 4:30 PM</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-bread-slice me-2"></i>Producto</th>
                            <th class="text-center">Cantidad Requerida</th>
                            <th class="text-center">Unidades por Kg*</th>
                            <th class="text-center">Kg Aproximados*</th>
                            <th class="text-center">Prioridad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_unidades = 0;
                        $total_kg = 0;
                        
                        // Ordenar por cantidad descendente
                        uasort($produccion, function($a, $b) {
                            return $b['cantidad_total'] - $a['cantidad_total'];
                        });
                        
                        foreach ($produccion as $id_producto => $datos): 
                            $total_unidades += $datos['cantidad_total'];
                            
                            // Estimación de unidades por kilo (esto debería venir de la BD)
                            $unidades_por_kilo = 20; // Valor por defecto para panes
                            if (strpos(strtolower($datos['nombre']), 'empanada') !== false) {
                                $unidades_por_kilo = 12;
                            } elseif (strpos(strtolower($datos['nombre']), 'torta') !== false) {
                                $unidades_por_kilo = 1;
                            }
                            
                            $kg_aproximados = ceil($datos['cantidad_total'] / $unidades_por_kilo);
                            $total_kg += $kg_aproximados;
                            
                            // Determinar prioridad basada en cantidad
                            $prioridad = 'Media';
                            $badge_class = 'bg-warning';
                            if ($datos['cantidad_total'] >= 100) {
                                $prioridad = 'Alta';
                                $badge_class = 'bg-danger';
                            } elseif ($datos['cantidad_total'] <= 20) {
                                $prioridad = 'Baja';
                                $badge_class = 'bg-success';
                            }
                        ?>
                            <tr>
                                <td>
                                    <strong><?= $datos['nombre'] ?></strong>
                                    <br>
                                    <small class="text-muted">ID: <?= $id_producto ?></small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6">
                                        <?= number_format($datos['cantidad_total']) ?> unidades
                                    </span>
                                </td>
                                <td class="text-center text-muted"><?= $unidades_por_kilo ?></td>
                                <td class="text-center">
                                    <strong><?= $kg_aproximados ?> kg</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badge_class ?>"><?= $prioridad ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-warning">
                        <tr>
                            <th><strong>TOTALES</strong></th>
                            <th class="text-center">
                                <strong><?= number_format($total_unidades) ?> unidades</strong>
                            </th>
                            <th class="text-center">-</th>
                            <th class="text-center">
                                <strong><?= $total_kg ?> kg aprox.</strong>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="alert alert-warning mt-3">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Notas Importantes</h6>
                <ul class="mb-0">
                    <li><strong>*</strong> Los valores de "Unidades por Kg" y "Kg Aproximados" son estimaciones. Ajustar según especificaciones reales.</li>
                    <li>Esta hoja incluye la <strong>descomposición automática de packs</strong> en sus componentes individuales.</li>
                    <li>Verificar disponibilidad de ingredientes antes de iniciar la producción.</li>
                    <li>Considerar productos con mayor prioridad para producir primero.</li>
                </ul>
            </div>
            
            <div class="text-center mt-4">
                <button class="btn btn-success me-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Imprimir Hoja de Producción
                </button>
                <button class="btn btn-info me-2" disabled>
                    <i class="fas fa-file-excel me-2"></i>Exportar a Excel
                </button>
                <button class="btn btn-warning" onclick="location.reload()">
                    <i class="fas fa-sync me-2"></i>Actualizar Datos
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>


<style>
@media print {
    .no-print, .btn, .card-header, .alert, nav, footer {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .table {
        font-size: 12px;
    }
}
</style>

<?php include '../app/views/layouts/footer.php'; ?>