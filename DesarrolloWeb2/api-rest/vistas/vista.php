<?php


$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

if (count(array_filter($arrayRutas)) == 2) {
    $json = array(
        "detalle" => "no encontrado"
    );
    echo json_encode($json, true);
    return;
} else {
    if (count(array_filter($arrayRutas)) == 3) {
        $token = null;
        $clientes = ModeloUsuario::index("usuario");

        if (array_filter($arrayRutas)[3] == "usuario") {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
                // Leer el cuerpo de la solicitud
                $inputJSON = file_get_contents('php://input');
                $input = json_decode($inputJSON, true);

                // Verificar que se recibieron los campos necesarios
                if (isset($input["email"]) && isset($input["password"])) {
                    $datos = array(
                        "email" => $input["email"],
                        "password" => $input["password"]
                    );

                    $usuario = new ControladorUsuarios();
                    $usuario->validarUsuario($datos);
                } else {
                    echo json_encode(array("status" => 400, "detalle" => "Faltan campos 'email' y/o 'password'"), true);
                }
            } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
                $usuario = new ControladorUsuarios();
                $usuario->index();
            }
        }

        // Otros casos (pacientes, medicos, citas)
        if (array_filter($arrayRutas)[3] == "pacientes") {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
                $datos = array(
                    "Nombre" => $_POST["nombre"],
                    "Telefono" => $_POST["telefono"],
                    "Direccion" => $_POST["direccion"]
                );

                $paciente = new ControladorPacientes();
                $paciente->create($datos);
            } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
                $paciente = new ControladorPacientes();
                $paciente->index();
            }
        }

        if (array_filter($arrayRutas)[3] == "medicos") {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
                $medicos = new ControladorMedicos();
                $medicos->index();
            } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
                $datos = array(
                    "Nombre" => $_POST["nombre"],
                    "Telefono" => $_POST["telefono"],
                    "Especialidad" => $_POST["especialidad"]
                );

                $medicos = new ControladorMedicos();
                $medicos->create($datos);
            }
        }
        
        if (array_filter($arrayRutas)[3] == "citas") {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
                $paciente = new ControladorCitas();
                $paciente->index();
            } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
                $datos = array(
                    "Fecha_Cita" => $_POST["fecha"],
                    "IdPaciente" => $_POST["idpaciente"],
                    "IdMedico" => $_POST["idmedico"],
                    "No_Consultorio" => $_POST["noConsul"]
                );

                $paciente = new ControladorCitas();
                $paciente->create($datos);
            }
        }
    } else {
        if (array_filter($arrayRutas)[3] == "pacientes" && is_numeric(array_filter($arrayRutas)[4])) {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
                $pacientes = new ControladorPacientes();
                $pacientes->show(array_filter($arrayRutas)[4]);
            }

            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT") {
                $datos = array();
                parse_str(file_get_contents('php://input'), $datos);
                $editarpacientes = new ControladorPacientes();
                $editarpacientes->update(array_filter($arrayRutas)[4], $datos);
            }

            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE") {
                $borrarmedico = new ControladorPacientes();
                $borrarmedico->delete(array_filter($arrayRutas)[4]);
            }


            
        } else if (array_filter($arrayRutas)[3] == "medicos" && is_numeric(array_filter($arrayRutas)[4])) {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
                $medicos = new ControladorMedicos();
                $medicos->show(array_filter($arrayRutas)[4]);
            }
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT") {
                $datos = array();
                parse_str(file_get_contents('php://input'), $datos);
                $editarmedicos = new ControladorMedicos();
                $editarmedicos->update(array_filter($arrayRutas)[4], $datos);
            }

            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE") {
                $borrarmedico = new ControladorMedicos();
                $borrarmedico->delete(array_filter($arrayRutas)[4]);
            }
        } 
    }
}
?>
