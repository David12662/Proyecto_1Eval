<?php

class Producto{
    private $idProducto;
    private $nombre;
    private $origen;
    private $foto;
    private $categoria;
    private $precio;
    
    


    public function  __construct($nombre,$origen=null,$foto = null, $categoria = null, $precio)
    {
        $this->nombre = $nombre;
        $this->origen = $origen;
        $this->foto = $foto;
        $this->categoria = $categoria;
        $this->precio = $precio;
    }


    public function insert($conexion) {
        try {
            $consulta = $conexion->prepare("INSERT INTO productos (nombre, origen, foto, categoria, precio) VALUES (?, ?, ?, ?, ?)");

            $consulta->bindParam(1, $this->nombre, PDO::PARAM_STR);
            $consulta->bindParam(2, $this->origen, PDO::PARAM_STR);
            $consulta->bindParam(3, $this->foto, PDO::PARAM_STR);
            $consulta->bindParam(4, $this->categoria, PDO::PARAM_STR);
            $consulta->bindParam(5, $this->precio, PDO::PARAM_INT);

            $consulta->execute();

        } catch(PDOException $e) {
            $dato = "¡Error!: " . $e->getMessage() . "<br/>";
            require "vistas/mensaje.php";
            die();
        }
    }

    public static function getAll($conexion){
        $sql="SELECT * FROM productos";
        $result= $conexion->query($sql);
        $productos=[];
         while($row = $result->fetch()){
             $producto = new Producto($row['nombre'],$row['origen'],$row['foto'],$row['categoria'],$row['precio']);
             $producto->idProducto=$row['idProducto'];
             $productos[]=$producto;
         }
        return $productos;
    }


    public static function obtenerPorId($conexion,$idProducto){
        $sql = "SELECT * FROM productos WHERE idProducto = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(1, $idProducto, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $producto = new Producto($row['nombre'], $row['origen'], $row['foto'], $row['categoria'], $row['precio']);
            $producto->idProducto = $row['idProducto'];
            return $producto;
        }
        return null;
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