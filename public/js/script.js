// JavaScript para Panadería Barrios

document.addEventListener('DOMContentLoaded', function() {
    
    // Agregar producto al carrito
    document.querySelectorAll('.agregar-carrito').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const originalText = this.innerHTML;
            
            // Mostrar loading
            this.innerHTML = '<span class="loading"></span> Agregando...';
            this.disabled = true;
            
            fetch('index.php?controller=carrito&action=agregar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_producto=${productId}&cantidad=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    this.innerHTML = '<i class="fas fa-check me-1"></i>¡Agregado!';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    
                    // Mostrar notificación
                    showNotification('Producto agregado al carrito', 'success');
                    
                    // Restaurar botón después de 2 segundos
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                        this.disabled = false;
                    }, 2000);
                } else {
                    // Mostrar error
                    showNotification(data.message || 'Error al agregar producto', 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error de conexión', 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
    
    // Manejar cantidad en carrito
    document.querySelectorAll('.cantidad-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const row = this.closest('[data-producto]');
            const productId = row.getAttribute('data-producto');
            const input = row.querySelector('.cantidad-input');
            let cantidad = parseInt(input.value);
            
            if (action === 'increase') {
                cantidad++;
            } else if (action === 'decrease' && cantidad > 1) {
                cantidad--;
            }
            
            input.value = cantidad;
            actualizarCantidadCarrito(productId, cantidad, row);
        });
    });
    
    // Eliminar producto del carrito
    document.querySelectorAll('.eliminar-producto').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('[data-producto]');
            const productId = row.getAttribute('data-producto');
            
            if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                eliminarProductoCarrito(productId, row);
            }
        });
    });
    
    // Animaciones de entrada
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);
    
    // Observar elementos para animación
    document.querySelectorAll('.producto-card, .categoria-card').forEach(card => {
        observer.observe(card);
    });
});

// Función para actualizar cantidad en carrito
function actualizarCantidadCarrito(productId, cantidad, row) {
    fetch('index.php?controller=carrito&action=actualizar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_producto=${productId}&cantidad=${cantidad}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar subtotal del producto
            const precio = parseFloat(row.querySelector('small').textContent.replace('S/ ', '').replace(' c/u', ''));
            const subtotal = precio * cantidad;
            row.querySelector('strong').textContent = `S/ ${subtotal.toFixed(2)}`;
            
            // Actualizar totales
            document.getElementById('subtotal').textContent = `S/ ${parseFloat(data.total).toFixed(2)}`;
            document.getElementById('total').textContent = `S/ ${parseFloat(data.total).toFixed(2)}`;
        } else {
            showNotification('Error al actualizar cantidad', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión', 'error');
    });
}

// Función para eliminar producto del carrito
function eliminarProductoCarrito(productId, row) {
    fetch('index.php?controller=carrito&action=eliminar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_producto=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animar eliminación
            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(-100%)';
            
            setTimeout(() => {
                row.remove();
                
                // Verificar si el carrito está vacío
                const remainingProducts = document.querySelectorAll('[data-producto]');
                if (remainingProducts.length === 0) {
                    location.reload();
                }
            }, 300);
            
            showNotification('Producto eliminado del carrito', 'success');
        } else {
            showNotification('Error al eliminar producto', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión', 'error');
    });
}

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} notification`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close btn-close-white float-end" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Validación de formularios
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showNotification('Por favor complete todos los campos obligatorios', 'error');
        }
    });
});

// Smooth scroll para enlaces internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});