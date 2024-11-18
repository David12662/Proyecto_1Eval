
async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hash = await crypto.subtle.digest("SHA-256", data);
    return Array.from(new Uint8Array(hash))
                .map(b => b.toString(16).padStart(2, "0"))
                .join("");
}

document.addEventListener("DOMContentLoaded", function() {
    const loginBtn = document.getElementById("login-btn");


    loginBtn.addEventListener("click", async function(event) {
        event.preventDefault();
        const datos = {
            action: "validar",
            email: document.getElementById("usernamelogin").value.trim(),
            password: document.getElementById("passwordlogin").value.trim(),
            
        };

        if (!datos.email || !datos.password) {
            alert("Por favor ingresa todos los campos.");
            return;
        }

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

    
        alert("Inicio de sesión exitoso.");
        window.location.href = "Indice.html";
    });
});