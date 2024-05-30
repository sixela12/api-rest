<?php


require_once "conexion.php";

class ModeloMedicos{


    static public function index($tabla){
        
            $stm = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            

            $stm->execute();
    
            return $stm->fetchAll(PDO::FETCH_CLASS);
    
            $stm->close();
    
            $stm=null;

        
        

    }

    static public function create($tabla, $datos){

        $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(Nombre, Telefono, Especialidad) VALUES (:Nombre, :Telefono, :Especialidad)");

        $stmt -> bindParam(":Nombre", $datos["Nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":Telefono", $datos["Telefono"], PDO::PARAM_STR);
		$stmt -> bindParam(":Especialidad", $datos["Especialidad"], PDO::PARAM_STR);		

        if($stmt->execute()){

			return "ok";

		}else{

			print_r(Conexion::conectar()->errorInfo());
		}

		$stmt-> close();

		$stmt = null;

    }


    static public function show($tabla, $id){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id=:id");
        $stmt -> bindParam(":id", $id, PDO::PARAM_INT);		

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);

        $stmt->close();

        $stmt=null;

    }

    static public function update($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("UPDATE medicos SET Nombre = :Nombre, Telefono = :Telefono, Especialidad = :Especialidad WHERE Id=:Id");


        $stmt -> bindParam(":Id", $datos["Id"], PDO::PARAM_INT);
        $stmt -> bindParam(":Nombre", $datos["Nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":Telefono", $datos["Telefono"], PDO::PARAM_STR);
		$stmt -> bindParam(":Especialidad", $datos["Especialidad"], PDO::PARAM_STR);		

        if($stmt->execute()){

			return "ok";

		}else{

			print_r(Conexion::conectar()->errorInfo());
		}

		$stmt-> close();

		$stmt = null;

    }

    static public function delete($tabla, $id){

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE Id=:id");
        $stmt -> bindParam(":id", $id, PDO::PARAM_INT);	

        if($stmt->execute()){

			return "ok";

		}else{

			print_r(Conexion::conectar()->errorInfo());
		}

        $stmt->close();

        $stmt=null;

    }


    
}