<?php
    chdir("../../"); 
    require_once "clases/Producto.php";
    require_once "config/config.php";
    require_once "clases/DB.php";

    class ProductoService {
        private $db;


        public function __construct()
        {
            $base = new Base();
            $this->db =$base-> __get("link");
        }

        public function getall(){
            try{
                $productos= Producto::getAll($this->db);

                $productosdevueltos = array_map(function($producto){
                    return[
                        "idProducto" => $producto->__get("idProducto"),
                        "nombre" => $producto->__get("nombre"),
                        "origen" => $producto->__get("origen"),
                        "foto" => $producto->__get("foto"),
                        "categoria" => $producto->__get("categoria"),
                        "precio" => $producto->__get("precio")   
                    ];   
                },$productos);
                return json_encode(["status" => "success", "productos" => $productosdevueltos]);  
            }catch(PDOException $e){
                return json_encode(["status" => "error", "message" => "Error al obtener productos: " . $e->getMessage()]);
            }
        }
        
        
        public function getbyID($id){
            try{
                $producto = Producto::obtenerPorId($this->db,$id);
                if($producto){
                    return json_encode([
                        "status" => "success",
                        "producto" => [
                        "idProducto" => $producto->__get("idProducto"),
                        "nombre" => $producto->__get("nombre"),
                        "origen" => $producto->__get("origen"),
                        "foto" => $producto->__get("foto"),
                        "categoria" => $producto->__get("categoria"),
                        "precio" => $producto->__get("precio")   
                        ]
                        ]);
                }else{
                    return json_encode(["status" => "error", "message" => "Producto no encontrado"]);
                }
            }catch(PDOException $e){
                return json_encode(["status" => "error", "message" => "Error al obtener producto: " . $e->getMessage()]);
            }
        }
    }

    header("Content-Type: application/json");

    $service = new ProductoService();

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $idProducto = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($idProducto) {
            echo $service->getById($idProducto);
        } else {
            echo $service->getAll();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Método no soportado"]);
    }

?>