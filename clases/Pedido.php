<?php
require_once "liniasPedido.php";

class Pedido{
    private $idPedido;
    private $fecha;
    private $dirEntrega;
    private $nTarjeta;
    private $fechaCaducidad;
    private $matriculaRepartidor;
    private $dniCliente;
    private $productos;


    public function __construct($idPedido,$fecha = null, $dirEntrega, $nTarjeta="", $fechaCaducidad="", $matriculaRepartidor="", $dniCliente,$productos) {
        $this->idPedido = $idPedido;
        $this->fecha = $fecha ?? date('Ymd');
        $this->dirEntrega = $dirEntrega;
        $this->nTarjeta = $nTarjeta;
        $this->fechaCaducidad = $fechaCaducidad;
        $this->matriculaRepartidor= $matriculaRepartidor;
        $this->dniCliente=$dniCliente;
        $this->productos=$productos;

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

    public static function calcularNuevaIdPedido($conexion){
        try{
            $consulta = $conexion->prepare("SeLECT MAX(idPedido) FROM pedidos");            
            $consulta->execute();
            $idPedido = $consulta->fetchColumn();
            return $idPedido+1;
        }catch (PDOException $e){
            $error = "Error al calcular id de pedido: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    }

    public  function insertarPedido($conexion){
        try{
            $sql=$conexion->prepare("INSERT INTO pedidos (idPedido,fecha,dirEntrega,nTarjeta,fechaCaducidad,matriculaRepartidor,dniCliente) values(?,?,?,?,?,?,?)");
            $sql->bindParam(1, $this->idPedido, PDO::PARAM_INT);
            $sql->bindParam(2, $this->fecha, PDO::PARAM_STR);
            $sql->bindParam(3, $this->dirEntrega, PDO::PARAM_STR);
            $sql->bindParam(4, $this->nTarjeta, PDO::PARAM_STR);
            $sql->bindParam(5, $this->fechaCaducidad, PDO::PARAM_STR);
            $sql->bindParam(6, $this->matriculaRepartidor, PDO::PARAM_STR);
            $sql->bindParam(7, $this->dniCliente, PDO::PARAM_STR);
            $sql->execute();

            foreach($this->productos as $producto){
                $producto->insertarLiniasPedido($conexion);
            }
        }catch(PDOException $e){
            $error = "Error al insertar pedido: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    }

    public static function getPedido($conexion,$idPedido){
        try{
            $sql=$conexion->prepare("SELECT idPedido,fecha,dirEntrega,nTarjeta,fechaCaducidad,matriculaRepartidor,dniCliente FROM pedidos WHERE idPedido=?");
            $sql->bindParam(1, $idPedido, PDO::PARAM_INT);
            $sql->execute();
            $pedido=$sql->fetch(PDO::FETCH_ASSOC);
            $productos=LiniasPedido::recuperarLiniasPedido($conexion,$idPedido);
            return new Pedido($pedido["idPedido"],$pedido["fecha"],$pedido["dirEntrega"],$pedido["nTarjeta"],$pedido["fechaCaducidad"],$pedido["matriculaRepartidor"],$pedido["dniCliente"],$productos);
        }catch(PDOException $e){
            $error = "Error al obtener pedido: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    }
    


}
?>