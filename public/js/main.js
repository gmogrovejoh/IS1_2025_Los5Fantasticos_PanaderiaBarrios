document.addEventListener('DOMContentLoaded', function() {
    
    // -------------------------------------------------------------------------
    // CONFIGURACIÓN
    // -------------------------------------------------------------------------
    // IMPORTANTE: Asegúrate de que esta URL coincida con tu carpeta del proyecto
    const BASE_URL = 'https://lavender-meerkat-667046.hostingersite.com/'; 

    console.log('Sistema cargado. URL Base:', BASE_URL);


    // -------------------------------------------------------------------------
    // MÓDULO 1: AGREGAR AL CARRITO (Desde Pedido Rápido o Catálogo)
    // -------------------------------------------------------------------------
    
    // Detectar clic en botones de "Agregar"
    const botonesAgregar = document.querySelectorAll('.btn-agregar');
    
    botonesAgregar.forEach(btn => {
        btn.addEventListener('click', function() {
            // Obtener datos del DOM relativos al botón presionado
            const cardBody = this.closest('.card-body');
            const idProducto = this.dataset.id;
            const inputCantidad = cardBody.querySelector('.input-cantidad');
            const cantidad = inputCantidad ? inputCantidad.value : 1;

            if (!cantidad || cantidad <= 0) {
                alert("⚠️ Por favor ingrese una cantidad válida (mayor a 0)");
                return;
            }

            // Llamar a la función centralizada
            procesarAgregarCarrito(idProducto, cantidad);
        });
    });

    // Función AJAX para agregar/actualizar
    function procesarAgregarCarrito(id, cantidad) {
        const formData = new FormData();
        formData.append('id_producto', id);
        formData.append('cantidad', cantidad);
        // Nota: Ya no enviamos monto_solicitado, el sistema es 100% por unidades.

        fetch(BASE_URL + 'carrito/agregar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mensaje de éxito simple
                alert("✅ Producto agregado/actualizado correctamente");
                // Opcional: Si estamos en la vista de carrito, recargar para ver totales
                if (window.location.href.includes('cliente/carrito')) {
                    location.reload();
                }
            } else {
                alert("❌ Error: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error f al intentar agregar al carrito.");
        });
    }


    // -------------------------------------------------------------------------
    // MÓDULO 2: GESTIÓN DEL CARRITO (Vista Carrito)
    // -------------------------------------------------------------------------

    // 2.1 Eliminar producto (Usamos delegación de eventos por seguridad)
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-producto')) {
            const btn = e.target.closest('.eliminar-producto');
            const idProducto = btn.dataset.producto;

            if(!confirm('¿Estás seguro de eliminar este producto?')) return;

            const formData = new FormData();
            formData.append('id_producto', idProducto);

            fetch(BASE_URL + 'carrito/eliminar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    location.reload(); // Recargar para actualizar la tabla y totales
                } else {
                    alert('Error al eliminar: ' + data.message);
                }
            });
        }
    });

    // 2.2 Actualizar cantidad desde el input del carrito
    const inputsCarrito = document.querySelectorAll('.cantidad-input-cart');
    inputsCarrito.forEach(input => {
        input.addEventListener('change', function() {
            const id = this.dataset.producto;
            const nuevaCantidad = this.value;

            if(nuevaCantidad > 0) {
                procesarAgregarCarrito(id, nuevaCantidad);
            } else {
                alert("La cantidad debe ser mayor a 0");
                location.reload(); // Revertir cambio visual
            }
        });
    });


    // -------------------------------------------------------------------------
    // MÓDULO 3: CHECKOUT (Cálculo de Envíos y Promociones)
    // -------------------------------------------------------------------------
    
    // Solo ejecutamos esto si estamos en la página de checkout (si existen los elementos)
    const radioEntrega = document.querySelectorAll('input[name="tipo_entrega"]');
    
    if (radioEntrega.length > 0) {
        const radioDireccion = document.querySelectorAll('input[name="id_direccion"]');
        const spanEnvio = document.getElementById('costo-envio-display');
        const spanTotal = document.getElementById('total-pagar-display');
        const divDirecciones = document.getElementById('contenedor-direcciones');
        
        // Elementos de la barra de progreso
        const divPromo = document.getElementById('promo-envio-container');
        const txtPromo = document.getElementById('mensaje-promo-texto');
        const barPromo = document.getElementById('barra-promo');

        function actualizarCostos() {
            // 1. Obtener tipo de entrega seleccionado
            let tipo = 'RECOJO_TIENDA';
            radioEntrega.forEach(r => { if(r.checked) tipo = r.value; });

            // 2. Obtener dirección seleccionada
            let idDir = null;
            radioDireccion.forEach(r => { if(r.checked) idDir = r.value; });

            // 3. Mostrar u ocultar lista de direcciones
            if (divDirecciones) {
                divDirecciones.style.display = (tipo === 'DOMICILIO') ? 'block' : 'none';
            }

            // 4. Petición AJAX al servidor para calcular
            const fd = new FormData();
            fd.append('tipo_entrega', tipo);
            if(idDir) fd.append('id_direccion', idDir);

            fetch(BASE_URL + 'pedido/recalcularTotales', {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // A. Actualizar Costo de Envío Visual
                    if(spanEnvio) {
                        if (data.es_gratis) {
                            spanEnvio.innerHTML = '<span class="badge bg-success">GRATIS</span> <small class="text-decoration-line-through text-muted">S/ ' + data.costo_envio + '</small>';
                        } else {
                            spanEnvio.textContent = 'S/ ' + data.costo_envio;
                        }
                    }

                    // B. Actualizar Total a Pagar
                    if(spanTotal) {
                        spanTotal.textContent = 'S/ ' + data.total;
                    }

                    // C. Actualizar Barra de Progreso (Envío Gratis)
                    if (tipo === 'DOMICILIO' && data.mensaje_promo && divPromo) {
                        divPromo.style.display = 'block';
                        txtPromo.innerHTML = data.mensaje_promo;
                        barPromo.style.width = data.porcentaje + '%';
                        
                        // Estilos según porcentaje
                        if (data.porcentaje >= 100) {
                            divPromo.classList.remove('border');
                            divPromo.classList.add('border-success');
                            barPromo.classList.remove('progress-bar-animated');
                            barPromo.classList.add('bg-success');
                        } else {
                            divPromo.classList.remove('border-success');
                            divPromo.classList.add('border');
                            barPromo.classList.add('progress-bar-animated');
                            // Si quieres cambiar color cuando falta poco:
                            // barPromo.classList.remove('bg-success');
                            // barPromo.classList.add('bg-warning'); 
                        }
                    } else {
                        // Ocultar barra si es Recojo o no hay promo configurada
                        if(divPromo) divPromo.style.display = 'none';
                    }
                }
            })
            .catch(err => console.error('Error calculando totales:', err));
        }

        // Listeners: Ejecutar cuando el usuario cambie algo
        radioEntrega.forEach(r => r.addEventListener('change', actualizarCostos));
        radioDireccion.forEach(r => r.addEventListener('change', actualizarCostos));

        // Ejecutar al cargar la página (para establecer estado inicial)
        actualizarCostos();
    }
});