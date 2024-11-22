<?php
session_start();
chdir("../../"); 
require_once "clases/liniasPedido.php";
require_once "clases/Pedido.php";
require_once "clases/Carrito.php";
require_once "config/config.php";
require_once "clases/DB.php";

class PedidoService
{
    private $db;
    public function __construct()
    {
        $base = new Base();
        $this->db = $base->__get("link");
    }

    public function insertarPedido($dirEntrega){
        $idpedido=Pedido::calcularNuevaIdPedido($this->db);
        $lineaspedido=[];
        $productos=Carrito::getProducts($this->db, $_SESSION["dniCliente"]);
        foreach($productos as $index => $producto){
            $lineaspedido[] = new LiniasPedido($idpedido,$index+1,$producto["idProducto"],$producto["cantidad"]);
        }
        $pedido=new Pedido($idpedido,null, $dirEntrega,"","","",$_SESSION["dniCliente"],$lineaspedido);
        $pedido->insertarPedido($this->db);
        return json_encode(["status" => "success", "idPedido" => $idpedido]);
    }

    public function getPedido($idPedido){
        return Pedido::getPedido($this->db,$idPedido);
    }
}


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, application/json");
header("Content-Type: application/json");

$service = new PedidoService();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    echo $service->insertarPedido($input["dirEntrega"]);
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    $service->getPedido($_GET["idPedido"]);
}
?>
