<?php include '../app/views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle me-2"></i>Crear Nuevo Pedido</h2>
        <a href="<?= BASE_URL ?>admin" class="btn btn-outline-secondary">Cancelar</a>
    </div>

    <form method="POST" action="<?= BASE_URL ?>admin/registrarPedido" id="formPedidoAdmin">
        
        <div class="row">
            <!-- COLUMNA IZQUIERDA: CONFIGURACIÓN -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">1. Datos del Cliente</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Cliente</label>
                            <select name="id_cliente" id="select_cliente" class="form-select" required>
                                <option value="">-- Buscar Cliente --</option>
                                <?php foreach($data['clientes'] as $c): ?>
                                    <option value="<?= $c['id_cliente'] ?>" data-rol="<?= $c['rol'] ?>">
                                        <?= $c['razon_social'] ? $c['razon_social'] . ' (' . $c['nombre'] . ')' : $c['nombre'] . ' ' . $c['apellidos'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comprobante</label>
                            <select name="tipo_comprobante" id="tipo_comprobante" class="form-select">
                                <option value="BOLETA">BOLETA</option>
                                <option value="FACTURA">FACTURA</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-dark">2. Entrega</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Fecha Entrega</label>
                            <!-- CAMBIO: min configurado para MAÑANA -->
                            <input type="date" name="fecha_entrega" class="form-control" 
                                min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                            <small class="text-danger d-block mt-1" style="font-size: 0.8em;">
                                * Solo se permiten pedidos a partir de mañana para producción.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Turno</label>
                            <select name="ventana_entrega" class="form-select">
                                <option value="MAÑANA">Mañana</option>
                                <option value="TARDE">Tarde</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Método</label>
                            <select name="tipo_entrega" id="tipo_entrega" class="form-select">
                                <option value="RECOJO_TIENDA">Recojo en Tienda</option>
                                <option value="DOMICILIO">Delivery</option>
                            </select>
                        </div>
                        
                        <!-- Select dinámico de direcciones -->
                        <div class="mb-3" id="div_direcciones" style="display:none;">
                            <label class="form-label">Dirección del Cliente</label>
                            <select name="id_direccion" id="id_direccion" class="form-select">
                                <option value="">Seleccione Cliente Primero</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-success">
                    <div class="card-body text-center">
                        <h5>Total Estimado</h5>
                        <h2 class="text-success" id="total_display">S/ 0.00</h2>
                        <small class="text-muted text-center d-block mb-3">*Sin incluir envío</small>
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="fas fa-check me-2"></i> Crear Pedido
                        </button>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: PRODUCTOS -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">3. Selección de Productos</div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 50%">Producto</th>
                                        <th style="width: 25%">Precio</th>
                                        <th style="width: 25%">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['productos'] as $p): ?>
                                        <?php 
                                            // Calcular precio unitario para JS
                                            if($p['unidades_base_b2b'] > 0) {
                                                $precio = $p['soles_base_b2b'] / $p['unidades_base_b2b'];
                                            } else {
                                                $precio = $p['precio_b2c'];
                                            }
                                        ?>
                                    <tr class="fila-producto" data-precio="<?= $precio ?>">
                                        <td>
                                            <strong><?= $p['nombre'] ?></strong><br>
                                            <small class="text-muted"><?= $p['categoria_nombre'] ?></small>
                                        </td>
                                        <td>
                                            <?php if($p['unidades_base_b2b'] > 0): ?>
                                                <small><?= $p['unidades_base_b2b'] ?>u = S/ <?= $p['soles_base_b2b'] ?></small><br>
                                                <strong>S/ <?= number_format($precio, 3) ?> c/u</strong>
                                            <?php else: ?>
                                                <strong>S/ <?= number_format($precio, 2) ?> c/u</strong>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="cantidades[<?= $p['id_producto'] ?>]" 
                                                   class="form-control input-cantidad-admin" 
                                                   min="0" value="0" placeholder="0">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const BASE_URL = '<?= BASE_URL ?>';
    const selectCliente = document.getElementById('select_cliente');
    const selectDireccion = document.getElementById('id_direccion');
    const selectTipoEntrega = document.getElementById('tipo_entrega');
    const divDirecciones = document.getElementById('div_direcciones');
    const selectComprobante = document.getElementById('tipo_comprobante');

    // 1. CARGAR DIRECCIONES AL CAMBIAR CLIENTE
    selectCliente.addEventListener('change', function() {
        const idCliente = this.value;
        const rol = this.options[this.selectedIndex].getAttribute('data-rol');

        // Autoseleccionar factura si es empresa
        if(rol === 'EMPRESA_FACTURA') {
            selectComprobante.value = 'FACTURA';
        } else {
            selectComprobante.value = 'BOLETA';
        }

        if(idCliente) {
            fetch(BASE_URL + 'admin/apiDirecciones/' + idCliente)
                .then(res => res.json())
                .then(data => {
                    selectDireccion.innerHTML = '';
                    if(data.length > 0) {
                        data.forEach(d => {
                            selectDireccion.innerHTML += `<option value="${d.id_direccion}">${d.alias} - ${d.calle}</option>`;
                        });
                    } else {
                        selectDireccion.innerHTML = '<option value="">El cliente no tiene direcciones</option>';
                    }
                });
        }
    });

    // 2. MOSTRAR/OCULTAR DIRECCIONES
    selectTipoEntrega.addEventListener('change', function() {
        if(this.value === 'DOMICILIO') {
            divDirecciones.style.display = 'block';
            selectDireccion.required = true;
        } else {
            divDirecciones.style.display = 'none';
            selectDireccion.required = false;
        }
    });

    // 3. CALCULAR TOTAL EN TIEMPO REAL
    const inputs = document.querySelectorAll('.input-cantidad-admin');
    const display = document.getElementById('total_display');

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.fila-producto').forEach(fila => {
            const input = fila.querySelector('.input-cantidad-admin');
            const cantidad = parseFloat(input.value) || 0;
            const precio = parseFloat(fila.dataset.precio);
            
            if(cantidad > 0) {
                total += cantidad * precio;
                // Resaltar fila activa
                fila.classList.add('table-success');
            } else {
                fila.classList.remove('table-success');
            }
        });
        display.textContent = 'S/ ' + total.toFixed(2);
    }

    inputs.forEach(input => {
        input.addEventListener('input', calcularTotal);
        // Seleccionar todo el texto al hacer click para escribir rápido
        input.addEventListener('focus', function() { this.select(); });
    });
});
</script>

<?php include '../app/views/layouts/footer.php'; ?>