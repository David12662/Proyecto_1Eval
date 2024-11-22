<?php

class LiniasPedido{
    private $idPedido;
    private $nlinea;
    private $idProducto;
    private $cantidad;


    public function __construct($idPedido, $nlinea, $idProducto, $cantidad) {
        $this->idPedido = $idPedido;
        $this->nlinea = $nlinea;
        $this->idProducto = $idProducto;
        $this->cantidad = $cantidad;
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


    public function insertarLiniasPedido($conexion){
        try{
            $sql=$conexion->prepare("INSERT INTO lineaspedidos (idPedido,nlinea,idProducto,cantidad) values(?,?,?,?)");
            $sql->bindParam(1, $this->idPedido, PDO::PARAM_INT);
            $sql->bindParam(2, $this->nlinea, PDO::PARAM_INT);
            $sql->bindParam(3, $this->idProducto, PDO::PARAM_INT);
            $sql->bindParam(4, $this->cantidad, PDO::PARAM_INT);
            $sql->execute();
        }catch(PDOException $e){
            $error = "Error al insertar linia de pedido: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    }


    public static function recuperarLiniasPedido($conexion,$idPedido){
        try{
            $sql=$conexion->prepare("SELECT idPedido,nlinea,idProducto,cantidad FROM lineaspedidos WHERE idPedido=?");
            $sql->bindParam(1, $idPedido , PDO::PARAM_INT);
            $sql->execute();
            $liniasPedido=$sql->fetchAll(PDO::FETCH_ASSOC);
            $productos = [];
            foreach($liniasPedido as $liniaPedido){
                $productos[] = new LiniasPedido($liniaPedido["idPedido"],$liniaPedido["nlinea"],$liniaPedido["idProducto"],$liniaPedido["cantidad"]);
            }
            return $productos;
        }catch(PDOException $e){
            $error = "Error al recuperar linias de pedido: " . $e->getMessage();
            require "../vistas/mensaje.php";
            die();
        }
    }

}
?>