<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="fas fa-clipboard-list me-2"></i>Hoja de Producción</h2>
        <button onclick="window.print()" class="btn btn-dark"><i class="fas fa-print me-2"></i>Imprimir</button>
    </div>

    <!-- Filtros (No imprimir) -->
    <div class="card mb-4 no-print bg-light">
        <div class="card-body">
            <form method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label>Fecha de Entrega</label>
                    <input type="date" name="fecha" class="form-control" value="<?= $data['fecha'] ?>">
                </div>
                <div class="col-md-4">
                    <label>Turno</label>
                    <select name="ventana" class="form-select">
                        <option value="MAÑANA" <?= $data['ventana']=='MAÑANA'?'selected':'' ?>>Mañana</option>
                        <option value="TARDE" <?= $data['ventana']=='TARDE'?'selected':'' ?>>Tarde</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Consultar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- HOJA IMPRIMIBLE -->
    <div class="card border-dark">
        <div class="card-header bg-white text-center border-bottom border-dark py-3">
            <h3>PANADERÍA BARRIOS - PLAN DE PRODUCCIÓN</h3>
            <h5 class="mb-0">
                Fecha: <?= date('d/m/Y', strtotime($data['fecha'])) ?> 
                | Turno: <?= $data['ventana'] ?>
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if(empty($data['produccion'])): ?>
                <div class="p-5 text-center text-muted">
                    <h4>No hay pedidos programados para este turno.</h4>
                </div>
            <?php else: ?>
                <table class="table table-bordered mb-0 table-striped">
                    <thead class="table-dark text-center" style="color: #000">
                        <tr>
                            <th class="fs-5">PRODUCTO</th>
                            <th class="fs-5" style="width: 200px;">CANTIDAD TOTAL</th>
                            <th class="fs-5" style="width: 150px;">CHECK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['produccion'] as $item): ?>
                        <tr>
                            <td class="fs-5 py-3 ps-4"><?= $item['nombre'] ?></td>
                            <td class="fs-4 text-center fw-bold"><?= $item['cantidad_total'] ?></td>
                            <td></td> <!-- Espacio para marcar con lapicero -->
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="card-footer bg-white text-muted text-end">
            Generado: <?= date('d/m/Y H:i') ?>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print, nav, footer, .btn { display: none !important; }
    body { background: white; }
    .container { max-width: 100%; margin: 0; padding: 0; }
    .card { border: 2px solid #000 !important; }
    th { background-color: #ddd !important; color: #000 !important; }
}
</style>

<?php include '../app/views/layouts/footer.php'; ?>