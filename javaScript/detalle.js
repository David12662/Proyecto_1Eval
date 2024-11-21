document.addEventListener('DOMContentLoaded', () =>{
    const params = new URLSearchParams(window.location.search);
    const idProducto = params.get('id');

    if(idProducto){
        cargarDetallesProducto(idProducto);
    }else{
        console.error("No hay ninguna id");
    }
});


async function cargarDetallesProducto(idProducto) {
    try {
        const response = await fetch(`../servicios/productos/ProductoService.php?id=${idProducto}`);
        const data = await response.json();

        if(data.status === "success"){
            const producto = data.producto;
            mostrarDetallesProducto(producto);
            

        } else {
            console.error("Error al cargar" , data.message);
        }
        
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
    
}

function mostrarDetallesProducto(producto){
    document.getElementById('productimage').src=`../public/${producto.foto}`; 
    document.getElementById('nombre').textContent = producto.nombre;
    const precioBaseInput = document.createElement('input');
    precioBaseInput.type = 'hidden';
    precioBaseInput.id = 'precioBase';
    precioBaseInput.value = producto.precio.toFixed(2);
    document.body.appendChild(precioBaseInput);

    añadirEventosCantidad();

    actualizarPrecio();
}

function añadirEventosCantidad() {
    const minusButton = document.querySelector('.quantity__button--minus');
    const plusButton = document.querySelector('.quantity__button--plus');
    const input = document.querySelector('.quantity__input');

    if (minusButton && plusButton && input) {
        
        minusButton.addEventListener('click', () => {
            const value = parseInt(input.value, 10) || 1;
            if (value > 1) {
                input.value = value - 1;
                actualizarPrecio();
            }
        });

        
        plusButton.addEventListener('click', () => {
            const value = parseInt(input.value, 10) || 1;
            input.value = value + 1;
            actualizarPrecio();
        });

        input.addEventListener('input', () => {
            actualizarPrecio();
        });
    } else {
        console.error("Los botones o el input no están disponibles. Recarga la página.");
    }
}

function actualizarPrecio() {
    const input = document.querySelector('.quantity__input');
    const precioBase = parseFloat(document.querySelector('#precioBase').value);


    let cantidad = parseInt(input.value, 10);
    if (isNaN(cantidad) || cantidad <= 0) {
        cantidad = 1; 
        input.value = cantidad;
    }

    
    const total = precioBase * cantidad;

    document.getElementById('precio').textContent = total.toFixed(2);
}