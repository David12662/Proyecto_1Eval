<?php

    class Cliente{
        private $dniCliente;
        private $nombre;
        private $direccion;
        private $email;
        private $pwdhash;
        private $administrador;


        public function __construct($dniCliente, $nombre, $direccion, $email, $pwdhash, $administrador = false) {
            $this->dniCliente = $dniCliente;
            $this->nombre = $nombre;
            $this->direccion = $direccion;
            $this->email = $email;
            $this->pwdhash = $pwdhash;
            $this->administrador = $administrador;
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

        public function registrar($conexion) {
            try {
                $sql=$conexion->prepare("INSERT INTO clientes (dniCliente, nombre, direccion, email, pwd) VALUES (?, ?, ?, ?, ?)");
                $sql->bindParam(1, $this->dniCliente, PDO::PARAM_STR);
                $sql->bindParam(2, $this->nombre, PDO::PARAM_STR);
                $sql->bindParam(3, $this->direccion, PDO::PARAM_STR);
                $sql->bindParam(4, $this->email, PDO::PARAM_STR);
                $sql->bindParam(5, $this->pwdhash, PDO::PARAM_STR);
                $sql->execute();
                return true;
            }catch (PDOException $e){
                $error = "Error al registrar cliente: " . $e->getMessage();
                require "../vistas/mensaje.php";
                die();
            }
        }

        public static function validar($conexion,$email,$password) {
            try{
                $consulta = $conexion->prepare("SELECT * FROM clientes WHERE email = ?");
                $consulta->bindParam(1, $email, PDO::PARAM_STR);
                $consulta->execute();
                $cliente = $consulta->fetch(PDO::FETCH_ASSOC);
                
                if ($cliente && password_verify($password, $cliente['pwd'])) {
                    return new Cliente(
                        $cliente['dniCliente'],
                        $cliente['nombre'],
                        $cliente['direccion'],
                        $cliente['email'],
                        $cliente['pwd']
                    );
                }
                return null;

            }catch (PDOException $e){
                $error = "Error al validar cliente: " . $e->getMessage();
                require "../vistas/mensaje.php";
                die();
            }
        }


        public static function obtenerPorDNI($conexion,$dniCliente) {
            try{
                $consulta = $conexion->prepare("SELECT * FROM clientes WHERE dniCliente = ?");
                $consulta->bindParam(1,$dniCliente,PDO::PARAM_STR);
                $consulta->execute();
                $cliente  = $consulta->fetch(PDO::FETCH_ASSOC);

                if($cliente){
                    return new Cliente(
                        $cliente['dniCliente'],
                        $cliente['nombre'],
                        $cliente['direccion'],
                        $cliente['email'],
                        $cliente['passwordHash']
                    );
                }
                return null;
            }catch (PDOException $e){
                $error = "Error al obtener cliente: " . $e->getMessage();
                require "../vistas/mensaje.php";
                die();
            }
        }

    }
?>