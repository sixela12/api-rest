<?php


require_once "conexion.php";

class ModeloCitas{


    static public function getCitasDetalladas() {
        // Establecer conexión
        $conexion = Conexion::conectar();
    
        // Preparar la consulta con INNER JOIN
        $sql = "SELECT c.Id, c.Fecha_Cita, p.Nombre AS NombrePaciente, p.Telefono AS TelefonoPaciente, p.Dirección AS DireccionPaciente, 
                m.Nombre AS NombreMedico, m.Telefono AS TelefonoMedico, m.Especialidad, c.No_Consultorio
                FROM citas c
                INNER JOIN pacientes p ON c.IdPaciente = p.Id
                INNER JOIN medicos m ON c.IdMedico = m.Id";
    
        $stm = $conexion->prepare($sql);
    
        // Ejecutar la consulta
        $stm->execute();
    
        // Recuperar los resultados
        $resultados = $stm->fetchAll(PDO::FETCH_CLASS);
    
        // Cerrar la declaración
        $stm = null;
    
        // Devolver los resultados
        return $resultados;
    }
    

    static public function create($tabla, $datos){

        $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(Fecha_Cita, IdPaciente, IdMedico, No_Consultorio) VALUES (:fecha, :idpaciente, :idmedico, :noConsul)");

        $stmt -> bindParam(":fecha", $datos["Fecha_Cita"], PDO::PARAM_STR);
		$stmt -> bindParam(":idpaciente", $datos["IdPaciente"], PDO::PARAM_STR);
		$stmt -> bindParam(":idmedico", $datos["IdMedico"], PDO::PARAM_STR);		
        $stmt -> bindParam(":noConsul", $datos["No_Consultorio"], PDO::PARAM_STR);
        
        if($stmt->execute()){

			return "ok";

		}else{

			print_r(Conexion::conectar()->errorInfo());
		}

		$stmt-> close();

		$stmt = null;

    }
}