document.addEventListener('DOMContentLoaded', function() {
    const carritoIcono = document.getElementById('carrito-icono');
    const carritoContenido = document.getElementById('carrito-contenido');
    const carritoLista = document.getElementById('carrito-lista');
    const carritoTotal = document.getElementById('carrito-total');
    const checkoutButton = document.getElementById('carrito-checkout');
    const userId = document.body.getAttribute('data-user-id');

    function obtenerCarrito() {
        return JSON.parse(localStorage.getItem(`carrito_${userId}`)) || [];
    }

    function guardarCarrito(carrito) {
        localStorage.setItem(`carrito_${userId}`, JSON.stringify(carrito));
    }

    function actualizarCarritoUI() {
        const carrito = obtenerCarrito();
        if (carritoLista) {
            carritoLista.innerHTML = '';
            let total = 0;
            carrito.forEach(item => {
                const li = document.createElement('li');
                const itemText = document.createElement('span');
                itemText.textContent = `${item.nombre} x${item.cantidad} - €${(item.precio * item.cantidad).toFixed(2)}`;
                
                const btnEliminar = document.createElement('button');
                btnEliminar.textContent = 'Eliminar';
                btnEliminar.onclick = () => eliminarDelCarrito(item.id);
                
                li.appendChild(itemText);
                li.appendChild(btnEliminar);
                carritoLista.appendChild(li);
                total += item.precio * item.cantidad;
            });
            if (carritoTotal) {
                carritoTotal.textContent = `€${total.toFixed(2)}`;
            }
        }
    }

    function agregarAlCarrito(productoId, nombre, precio, cantidad = 1) {
        const carrito = obtenerCarrito();
        const productoExistente = carrito.find(item => item.id === productoId);
        if (productoExistente) {
            productoExistente.cantidad += cantidad;
        } else {
            carrito.push({ id: productoId, nombre, precio, cantidad });
        }
        guardarCarrito(carrito);
        actualizarCarritoUI();
    }

    function eliminarDelCarrito(productoId) {
        let carrito = obtenerCarrito();
        carrito = carrito.filter(item => item.id !== productoId);
        guardarCarrito(carrito);
        actualizarCarritoUI();
    }

    if (carritoIcono && carritoContenido) {
        carritoIcono.addEventListener('click', function() {
            carritoContenido.classList.toggle('show');
        });
    }

    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            const carrito = obtenerCarrito();
            if (carrito.length === 0) {
                alert('El carrito está vacío');
                return;
            }
            
            fetch('procesar_pago.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(carrito)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Vaciar el carrito local
                    localStorage.removeItem(`carrito_${userId}`);
                    actualizarCarritoUI();
                    alert('Compra realizada con éxito. Redirigiendo a la página principal...');
                    window.location.href = 'main.php';
                } else {
                    throw new Error(data.error || 'Error desconocido al procesar la compra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la compra: ' + error.message);
            });
        });
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-nombre');
            const productPrice = parseFloat(this.getAttribute('data-precio'));
            agregarAlCarrito(productId, productName, productPrice);
            alert(`${productName} ha sido añadido al carrito.`);
        });
    });

    // Cargar el carrito al iniciar la página
    actualizarCarritoUI();
});

