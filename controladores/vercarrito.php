<?php   
session_start();

if (isset($_SESSION["dniCliente"])) {
    header("Location: ../vistas/Confirmacion.html");
}else{
    header("Location: ../vistas/Registro.html");
}


?>