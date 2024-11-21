<?php
session_start();
chdir("../../");
require_once "clases/Carrito.php";
require_once "config/config.php";
require_once "clases/DB.php";

class CarritoService {
    private $db;

    public function __construct() {
        $base = new Base();
        $this->db = $base->__get("link");
    }

    public function getSessionIdentifier() {
        if (!isset($_SESSION["idUnico"])) {
            $_SESSION["idUnico"] = uniqid('anon_', true);
        }
        return $_SESSION["dniCliente"] ?? $_SESSION["idUnico"];
    }

    public function exists($idProducto) {
        $identifier = $this->getSessionIdentifier();
        return Carrito::exists($this->db, $identifier, $idProducto);
    }

    public function addProducto($idProducto, $cantidad) {
        $identifier = $this->getSessionIdentifier();
        return Carrito::addProducto($this->db, $identifier, $idProducto, $cantidad);
    }

    public function updateProduct($idProducto, $newCantidad) {
        $identifier = $this->getSessionIdentifier();
        return Carrito::updateProduct($this->db, $identifier, $idProducto, $newCantidad);
    }

    public function deleteProduct($idProducto) {
        $identifier = $this->getSessionIdentifier();
        return Carrito::deleteProduct($this->db, $identifier, $idProducto);
    }

    public function getProducts() {
        $identifier = $this->getSessionIdentifier();
        return Carrito::getProducts($this->db, $identifier);
    }
}

$service = new CarritoService();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        $response["error"] = "No se recibieron datos o el JSON está mal formado";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    $action = $input["action"] ?? null;
    $idProducto = $input["idProducto"] ?? null;
    $cantidad = $input["cantidad"] ?? null;

    $response = [];

    switch ($action) {
        case "exists":
            $response["exists"] = $idProducto ? $service->exists($idProducto) : "Error: idProducto no proporcionado";
            break;
        case "add":
            $response["success"] = ($idProducto && $cantidad) ? $service->addProducto($idProducto, $cantidad) : "Faltan parámetros";
            break;
        case "updateProduct":
            $response["success"] = ($idProducto && $cantidad) ? $service->updateProduct($idProducto, $cantidad) : "Error: faltan datos";
            break;
        case "deleteProduct":
            $response["success"] = $idProducto ? $service->deleteProduct($idProducto) : "Error: idProducto no proporcionado";
            break;
        case "getProducts":
            $response["success"] = $service->getProducts();
            break;
        default:
            $response["error"] = "Acción no válida";
    }

    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}