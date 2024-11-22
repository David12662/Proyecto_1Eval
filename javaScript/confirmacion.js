document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('../servicios/clientes/ClienteService.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'action': 'obtenerTodosLosDatosDeSesion',
            })
        });
        const data = await response.json();
        if (data.status === 'success') {
            const nombre=document.getElementById('nombre');
            const dni=document.getElementById('dni');
            const direccion=document.getElementById('direccion');
            const email=document.getElementById('email');
            email.value=data.usuario.email;
            nombre.value=data.usuario.nombre;
            dni.value= data.usuario.dniCliente=data.usuario.dnicliente;
            direccion.value=data.usuario.direccion;
            
        }
    } catch (error) {
        console.error('Error al obtener los datos de sesión:', error);
    }
});

document.querySelector('#confirmarCompra').addEventListener('click', async () => {
    const response = await fetch('../servicios/pedido/pedidoService.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            dirEntrega: document.getElementById('direccion').value
        })
    });
    const data = await response.json();
    if (data.status == "success" && data.idPedido > 0) {
        window.location.href = `../controladores/confirmar.php?idPedido=${data.idPedido}`;
    }
});
