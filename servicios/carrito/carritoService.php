<?php
chdir("../../");
require_once "clases/Carrito.php";
require_once "config/config.php";
require_once "clases/DB.php";

class CarritoService{
    private $db;



    public function __construct() {
        $base = new Base();
        $this->db = $base->__get("link");
    }

    public function exists($idProducto) {
        return Carrito::exists($this->db, $_SESSION["dniCliente"],$idProducto);
    }

    public function addProducto( $idProducto, $cantidad) {
        return Carrito::addProducto($this->db, $_SESSION["dniCliente"], $idProducto, $cantidad);
    }

    public function updateProduct($idProducto, $newCantidad) {
        return Carrito::updateProduct($this->db, $_SESSION["dniCliente"], $idProducto, $newCantidad);
    }

    public function deleteProduct($idProducto) {
        return Carrito::deleteProduct($this->db, $_SESSION["dniCliente"], $idProducto);
    }
    
    public function getProducts() {
        return Carrito::getProducts($this->db, $_SESSION["dniCliente"]);
    }

}

$service = new CarritoService();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $action = $input["action"] ?? null;
    
    $idProducto	= $input["idProducto"] ?? null;
    $cantidad = $input["cantidad"] ?? null;

    $response = [];

    switch ($action){
         case "exists":
            if($idProducto){
                $response["exists"] = $service->exists($idProducto);
            } else {
                $response["exists"] = "Error no se ha recibido el id del producto";
            }
            break;
        case "add":
            if($idProducto && $cantidad){
                $response["success"] = $service->addProducto($idProducto,$cantidad);
            } else {
                $response["error"] = "Faltan parametros por enviar";
            }
            break;
        case "updateProduct":
            if($idProducto && $cantidad){
                $response["success"] = $service->updateProduct($idProducto,$cantidad);
            } else {
                $response["error"] = "Error no se ha recibido el id del producto o la cantidad";
            }
            break;
        case "deleteProduct":
            if($idProducto){
                $response["success"] = $service->deleteProduct($idProducto);
            } else {
                $response["error"] = "Error no se ha recibido el id del producto";
            }
        case "getProducts":
            $response["success"] = $service->getProducts();
            break;
        default:
            $response["error"] = "Accion no valida";

        header("Content-Type: application/json");
        echo json_encode($response);

    }
}
