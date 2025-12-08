// JavaScript para Panadería Barrios
/*
document.addEventListener('DOMContentLoaded', function() {
    
    // Funciones para el carrito B2C
    initCarritoB2C();
    
    // Funciones para pedido rápido B2B
    initPedidoRapidoB2B();
    
    // Funciones generales
    initGeneral();
});

function initCarritoB2C() {
    // Agregar productos al carrito (B2C)
    document.querySelectorAll('.agregar-carrito-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(BASE_URL + 'carrito/agregar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Producto agregado al carrito', 'success');
                } else {
                    showAlert(data.message || 'Error al agregar producto', 'danger');
                }
            })
            .catch(error => {
                showAlert('Error de conexión', 'danger');
            });
        });
    });
    
    // Actualizar cantidad en carrito
    document.querySelectorAll('.cantidad-input').forEach(input => {
        input.addEventListener('change', function() {
            const idProducto = this.dataset.producto;
            const cantidad = this.value;
            
            const formData = new FormData();
            formData.append('id_producto', idProducto);
            formData.append('cantidad', cantidad);
            
            fetch(BASE_URL + 'carrito/actualizar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Recargar para actualizar totales
                } else {
                    showAlert(data.message || 'Error al actualizar', 'danger');
                }
            });
        });
    });
    
    // Eliminar productos del carrito
    document.querySelectorAll('.eliminar-producto').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Está seguro de eliminar este producto?')) {
                const idProducto = this.dataset.producto;
                
                const formData = new FormData();
                formData.append('id_producto', idProducto);
                
                fetch(BASE_URL + 'carrito/eliminar', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        showAlert(data.message || 'Error al eliminar', 'danger');
                    }
                });
            }
        });
    });
}

function initPedidoRapidoB2B() {
    // Calcular cantidades en tiempo real para panes
    document.querySelectorAll('.monto-input').forEach(input => {
        input.addEventListener('input', function() {
            const monto = parseInt(this.value) || 0;
            const unidades = parseInt(this.dataset.unidades);
            const soles = parseFloat(this.dataset.soles);
            
            if (monto > 0) {
                const cantidadCalculada = Math.floor((monto / soles) * unidades);
                const cantidadDiv = this.parentElement.nextElementSibling;
                const cantidadSpan = cantidadDiv.querySelector('.cantidad-valor');
                
                cantidadSpan.textContent = cantidadCalculada;
                cantidadDiv.style.display = 'block';
            } else {
                this.parentElement.nextElementSibling.style.display = 'none';
            }
        });
    });
    
    // Agregar productos por monto (panes)
    document.querySelectorAll('.agregar-monto').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.monto-input');
            const monto = parseInt(input.value);
            
            if (!monto || monto < 1) {
                showAlert('Ingrese un monto válido (número entero)', 'warning');
                return;
            }
            
            const formData = new FormData();
            formData.append('id_producto', this.dataset.producto);
            formData.append('monto_solicitado', monto);
            
            fetch(BASE_URL + 'carrito/agregar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Producto agregado al carrito', 'success');
                    input.value = '';
                    input.dispatchEvent(new Event('input')); // Limpiar cantidad calculada
                    actualizarContadorCarrito();
                } else {
                    showAlert(data.message || 'Error al agregar producto', 'danger');
                }
            });
        });
    });
    
    // Agregar empanadas por cantidad
    document.querySelectorAll('.agregar-empanada').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.cantidad-empanada');
            const cantidad = parseInt(input.value);
            
            if (!cantidad || cantidad < parseInt(input.min)) {
                showAlert(`Cantidad mínima: ${input.min} unidades`, 'warning');
                return;
            }
            
            const formData = new FormData();
            formData.append('id_producto', this.dataset.producto);
            formData.append('cantidad', cantidad);
            
            fetch(BASE_URL + 'carrito/agregar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Producto agregado al carrito', 'success');
                    input.value = input.min;
                    actualizarContadorCarrito();
                } else {
                    showAlert(data.message || 'Error al agregar producto', 'danger');
                }
            });
        });
    });
    
    // Validar que solo se ingresen números enteros
    document.querySelectorAll('.monto-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            // Solo permitir números
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
                e.preventDefault();
            }
        });
        
        input.addEventListener('blur', function() {
            // Asegurar que sea un número entero
            const valor = parseInt(this.value);
            if (isNaN(valor) || valor < 1) {
                this.value = '';
            } else {
                this.value = valor;
            }
        });
    });
}

function initGeneral() {
    // Validar fechas en checkout
    const fechaInput = document.getElementById('fecha_entrega');
    if (fechaInput) {
        fechaInput.addEventListener('change', function() {
            actualizarVentanasDisponibles(this.value);
        });
    }
    
    // Auto-dismiss alerts después de 5 segundos
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            if (alert.classList.contains('alert-success')) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
}

function actualizarVentanasDisponibles(fecha) {
    const ventanaSelect = document.getElementById('ventana_entrega');
    if (!ventanaSelect) return;
    
    // Lógica simplificada - en producción debería consultar al servidor
    const hoy = new Date().toISOString().split('T')[0];
    const manana = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    
    // Limpiar opciones
    ventanaSelect.innerHTML = '<option value="">Seleccionar horario</option>';
    
    if (fecha === manana) {
        // Para mañana, verificar hora de corte (simplificado)
        const horaActual = new Date().getHours();
        if (horaActual <= 22) { // Antes de las 10 PM
            ventanaSelect.innerHTML += '<option value="MAÑANA">Mañana (5:00 AM - 8:00 AM)</option>';
        }
        ventanaSelect.innerHTML += '<option value="TARDE">Tarde (5:00 PM - 8:00 PM)</option>';
    } else if (fecha === hoy) {
        // Para hoy, solo tarde si no pasó la hora
        const horaActual = new Date().getHours();
        if (horaActual <= 10) {
            ventanaSelect.innerHTML += '<option value="TARDE">Tarde (5:00 PM - 8:00 PM)</option>';
        }
    } else {
        // Para fechas futuras, ambas opciones
        ventanaSelect.innerHTML += '<option value="MAÑANA">Mañana (5:00 AM - 8:00 AM)</option>';
        ventanaSelect.innerHTML += '<option value="TARDE">Tarde (5:00 PM - 8:00 PM)</option>';
    }
}

function actualizarContadorCarrito() {
    // Función para actualizar el contador de items en carrito
    // En una implementación real, esto consultaría al servidor
    const contador = document.getElementById('items-carrito');
    if (contador) {
        const valorActual = parseInt(contador.textContent) || 0;
        contador.textContent = valorActual + 1;
    }
}

function showAlert(message, type = 'info') {
    // Crear y mostrar alerta temporal
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insertar al inicio del container
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-dismiss después de 3 segundos
        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.5s';
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 500);
        }, 3000);
    }
}

// Variable global para BASE_URL (definida en el HTML)
const BASE_URL = window.location.origin + '/panaderia-barrios/';

*/