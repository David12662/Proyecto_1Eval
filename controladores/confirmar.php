
<?php
require_once "../clases/DB.php";
require_once "../clases/Pedido.php";
require_once "../clases/Producto.php";

$base = new Base();
$db = $base->__get("link");
$pedido = Pedido::getPedido($db, $_GET["idPedido"]);

$html = "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Resumen del Pedido</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f4f4f4; }
        </style>
    </head>
    <body>
        <h1>Resumen del Pedido</h1>";


    $html .= "<h2>Datos del Pedido</h2>
    <p><strong>ID del Pedido:</strong> {$pedido->__get("idPedido")}</p>
    <p><strong>Fecha:</strong> {$pedido->__get("fecha")}</p>
    <p><strong>Dirección de Entrega:</strong> {$pedido->__get("dirEntrega")}</p>
    <p><strong>DNI del Cliente:</strong> {$pedido->__get("dniCliente")}</p>";


    $html .= "<h2>Productos</h2>
    <table>
        <tr>
            <th>#</th>
            <th>ID Producto</th>
            <th>Cantidad</th>
        </tr>";

    foreach ($pedido->__get('productos') as $producto) {
        $html .= "<tr>
            <td>{$producto->__get('nlinea')}</td>
            <td>" . Producto::obtenerPorId($db, $producto->__get('idProducto'))->__get('nombre') . "</td>
            <td>{$producto->__get('cantidad')}</td>
        </tr>";
    }

    $html .= "</table>";


    $html .= "<p>Gracias por realizar su compra con nosotros. ¡Esperamos verle pronto!</p>
    </body>
    </html>";

echo $html;
?>
