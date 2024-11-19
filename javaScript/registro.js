
async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hash = await crypto.subtle.digest("SHA-256", data);
    return Array.from(new Uint8Array(hash))
                .map(b => b.toString(16).padStart(2, "0"))
                .join("");
}

document.getElementById("register-btn").addEventListener("click", function () {
    const datos = {
        action: "registrar",
        dniCliente: document.getElementById("dni").value.trim(),
        nombre: document.getElementById("username").value.trim(),
        direccion: document.getElementById("direccion").value.trim(),
        email: document.getElementById("email").value.trim(),
        password: document.getElementById("contraseña").value.trim(),
        confirmarPassword: document.getElementById("confirmarcontraseña").value.trim()
    };

    if (datos.password !== datos.confirmarPassword) {
        alert("Las contraseñas no coinciden.");
        return;
    }

    if (!datos.dniCliente || !datos.nombre || !datos.direccion || !datos.email || !datos.password) {
        alert("Por favor complete todos los campos.");
        return;
    }

    fetch("../servicios/clientes/ClienteService.php", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datos),
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("El DNI ya esta registrado");
        }
    })
    

    fetch("../servicios/clientes/ClienteService.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(datos),
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Usuario registrado con éxito");
            window.location.href = "Login.html"; 
        } else {
            alert(data.message || "Error al registrar usuario");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error en el servidor. Inténtelo más tarde.");
    });
});