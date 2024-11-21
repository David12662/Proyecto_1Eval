document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#boton_agregar').forEach(boton => {
        boton.addEventListener('click', async () => {
            const id = new URLSearchParams(window.location.search).get('id');
            if (id) {
                await cargarDetallesProducto(id);
                console.log("Producto procesado correctamente");
            } else {
                console.error("ID del producto no encontrado en la URL.");
            }
        });
    });

    loadCart(); // Cargar el carrito inicial
    async function cargarDetallesProducto(idProducto) {
        try {
            // Obtener los detalles del producto
            const response = await fetch(`../servicios/productos/ProductoService.php?id=${idProducto}`);
            const data = await response.json();
    
            if (data.status === "success") {
                const producto = data.producto;
                mostrarDetallesProducto(producto);
    
                
                const existResponse = await fetch('../servicios/carrito/carritoService.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'exists', idProducto: idProducto })
                });
    
                const existData = await existResponse.json();
    
                let quantity = 1; 
                const input = document.querySelector('.quantity__input');
                if (input) {
                    quantity = parseInt(input.value, 10);
                }
    
                if (existData.exists) {
                    await fetch('../servicios/carrito/carritoService.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'updateProduct',
                            idProducto: idProducto,
                            cantidad: quantity
                        })
                    });
                } else {

                    await fetch('../servicios/carrito/carritoService.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'add',
                            idProducto: idProducto,
                            cantidad: quantity,
                            total: producto.precio * quantity
                        })
                    });
                }
                console.log("Datos recibidos:", data);
    
                
                await loadCart();
                const offcanvasElement = document.getElementById('cartOffcanvas');
                const bootstrapOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
                bootstrapOffcanvas.show();
            } else {
                console.error("Error al cargar el producto:", data.message);
            }
        } catch (error) {
            console.error("Error en cargarDetallesProducto:", error);
        }
    }
    


    function mostrarDetallesProducto(producto) {
        let productImage = document.getElementById('productimage');
        let productName = document.getElementById('nombrecarrito');

        if (!productImage) {
            productImage = document.createElement('img');
            productImage.id = 'productimage';
            productImage.alt = 'Imagen del producto';
            document.body.appendChild(productImage); 
            }

            if (!productName) {
                productName = document.createElement('h6');
                productName.id = 'nombrecarrito';
                document.body.appendChild(productName);
            }

            productImage.src = `../public/${producto.foto}`;
            productName.textContent = producto.nombre;

            const precioBaseInput = document.createElement('input');
            precioBaseInput.type = 'hidden';
            precioBaseInput.id = 'precioBase';
            precioBaseInput.value = producto.precio.toFixed(2);
            document.body.appendChild(precioBaseInput);
        }

    async function loadCart() {
        try {
            const response = await fetch('../servicios/carrito/carritoService.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'getProducts' })
            });
            const data = await response.json();

            const cartContainer = document.querySelector('#cartOffcanvas .offcanvas-body');
            cartContainer.innerHTML = '';
            console.log(data);

            if (!data.success || data.success.length === 0) {
                cartContainer.innerHTML = '<p class="text-center">El carrito está vacío.</p>';
                return;
            }

            let total = 0;



            data.success.forEach(producto => {
                cartContainer.innerHTML += `
                    <div class="mb-3">
                        <img src="../public/${producto.foto}" alt="Producto" class="img-thumbnail me-3">
                        <div>
                            <h6 class="mt-3">${producto.nombre}</h6>
                            <p>Precio: ${producto.precio.toFixed(2)} €</p>
                            <p>Cantidad: <input type="number" class="form-control-sm quantity__input" value="${producto.cantidad}" data-id="${producto.idProducto}"></p>
                        </div>
                        <div class="ms-auto">
                            <button class="btn btn-outline-danger btn-sm btn-delete" data-id="${producto.idProducto}">🗑️</button>
                        </div>
                    </div>
                `;

                total += producto.precio * producto.cantidad;   
            });

            cartContainer.innerHTML += `
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="fw-bold">Total: ${total.toFixed(2)} €</p>
                    <button class="btn btn-success" id="boton_tramitar">Tramitar Pedido</button>
                </div>
            `;

            attachCartEvents();
        } catch (error) {
            console.error('Error cargando el carrito:', error);
        }
}


async function  isValidated(){
    const response = await fetch('../controladores/vercarrito.php',{
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });
    window.location.href = response.url;
    

}



function attachCartEvents() {
    document.querySelectorAll('.form-control-sm').forEach(input => {
        input.addEventListener('change', async () => {
            const idProducto = input.getAttribute('data-id');
            const cantidad = parseInt(input.value);
            try {
                await fetch('../servicios/carrito/carritoService.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'updateProduct',
                        idProducto: idProducto,
                        cantidad: cantidad
                    })
                });

                loadCart();
            } catch (error) {
                console.error('Error actualizando producto:', error);
            }
        });

    });

    document.querySelectorAll('#boton_tramitar').forEach(boton => {
        boton.addEventListener('click', async () => {
            await isValidated();

        });

        
    });


    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', async () => {
            const idProducto = button.getAttribute('data-id');

            try {
                await fetch('../servicios/carrito/carritoService.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'deleteProduct',
                        idProducto: idProducto
                    })
                });

                loadCart();
            } catch (error) {
                console.error('Error eliminando producto:', error);
            }
        });
    });
}

loadCart();
});


