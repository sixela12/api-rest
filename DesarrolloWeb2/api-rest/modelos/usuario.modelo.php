<?php

require_once "conexion.php";

class ModeloUsuario
{
    static public function index($tabla)
    {
        $stm = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_CLASS);
        $stm->close();
        $stm = null;
    }

    static public function obtenerPorEmail($email)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function create($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(email, password, id_cliente, llave_secreta) VALUES (:email, :password, :id_cliente, :llave_secreta)");
        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
        $stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_STR);
        $stmt->bindParam(":llave_secreta", $datos["llave_secreta"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            print_r(Conexion::conectar()->errorInfo());
        }

        $stmt->close();
        $stmt = null;
    }
}

?>










