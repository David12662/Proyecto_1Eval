<?php



class Carrito{
    public $idCarrito;
    public $idProducto;
    public $cantidad;
    public $dniCliente;
    public $precio;



    public function __construct($idCarrito, $idProducto, $cantidad, $dniCliente, $precio) {
        $this->idCarrito = $idCarrito;
        $this->idProducto = $idProducto;
        $this->cantidad = $cantidad;
        $this->dniCliente = $dniCliente;
        $this->precio = $precio;
    }


    public static function exists($conexion, $dniCliente,$idProducto) {
        try{
        $sql = $conexion->prepare( "SELECT * FROM carrito WHERE dniCliente = ? AND idProducto = ?");
        $sql->bindParam(1, $dniCliente, PDO::PARAM_STR);
        $sql->bindParam(2, $idProducto, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            $error = "Error al obtener carrito: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    }

    public static function addProducto($conexion, $dniCliente, $idProducto, $cantidad) {
        try{
            $sql = $conexion->prepare( "INSERT INTO carrito (dniCliente, idProducto, cantidad) VALUES (?, ?, ?)");
            $sql->bindParam(1, $dniCliente, PDO::PARAM_STR);
            $sql->bindParam(2, $idProducto, PDO::PARAM_INT);
            $sql->bindParam(3, $cantidad, PDO::PARAM_INT);
            $sql->execute();
            return true;
        }catch (PDOException $e){
            $error = "Error al obtener carrito: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }   
    }

    public static function updateProduct($conexion, $dniCliente, $idProducto, $newCantidad) {
        try {
            if($newCantidad == 0){
                self::deleteProduct($conexion,$dniCliente,$idProducto);
            }else{
                $sql = $conexion->prepare("UPDATE carrito SET cantidad = ?, WHERE dniCliente = ? AND idProducto = ?");
                $sql->bindParam(1, $newCantidad, PDO::PARAM_INT);
                $sql->bindParam(2, $dniCliente, PDO::PARAM_STR);
                $sql->bindParam(3, $idProducto, PDO::PARAM_INT);
                $sql->execute();
            }
        } catch (PDOException $e) {
            $error = "Error al actualizar producto en el carrito: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    }

    public static function getProducts($conexion, $dniCliente) {
        try {
            $sql = $conexion->prepare("SELECT * FROM carrito WHERE dniCliente = ?");
            $sql->bindParam(1, $dniCliente, PDO::PARAM_STR);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Error al obtener productos del carrito: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    
    }

    public static function deleteProduct($conexion,$dniCliente,$idProducto){
        try{
            $sql=$conexion->prepare("DELETE FROM cattiro where dniCliente=? AND idProducto=?");
            $sql->bindParam(1, $dniCliente, PDO::PARAM_STR);
            $sql->bindParam(2, $idProducto, PDO::PARAM_INT);
            $sql->execute();
        } catch (PDOException $e){
            $error = "Error al eliminar producto del carrito: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }

    }


    public function __set($propiedad, $var){
        if(property_exists(__CLASS__, $propiedad)){
            $this->$propiedad = $var;
        }
    }
    public function __get($propiedad){
        if(property_exists(__CLASS__, $propiedad)){
            return $this->$propiedad;
        }
    }

}


?>