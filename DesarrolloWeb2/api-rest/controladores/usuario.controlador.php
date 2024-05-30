<?php


class ControladorUsuarios
{
    public function index()
    {
        $usuario = ModeloUsuario::index("usuario");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($usuario as $key => $value) {
                if ($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW'] == 
                    $value->id_cliente . ":" . $value->llave_secreta) {

                    $json = array(
                        "status" => 200,
                        "detalle" => "Registro exitoso, ha sido guardado"
                    );

                    echo json_encode($json, true);
                    return;
                }
            }
        }
    }

    public function validar($datos){
        $toke;
        $usuario=ModeloUsuario::index("Usuario");

        /*=============================================
        Generar credenciales del cliente
        =============================================*/
        $id_cliente= str_replace("$","c",crypt($datos["email"].$datos["password"] ,'$2a$07$afartwetsdAD52356FEDGsfhsd$'));
        $llave_secreta= str_replace("$","a",crypt($datos["password"].$datos["email"] ,'$2a$07$afartwetsdAD52356FEDGsfhsd$'));

        $datos = array("email"=>$datos["email"],
        "password"=>$datos["password"],
        "id_cliente"=>$id_cliente,
        "llave_secreta"=>$llave_secreta
        );

        $create=ModeloUsuario::create("Usuario", $datos);

        if($create == "ok"){

            $json=array(
                "status"=>404,
                "detalle"=> "se genero sus credenciales",
                "id_cliente"=>$id_cliente,
                "llave_secreta"=>$llave_secreta
            );
           echo json_encode($json,true);
          return;
        }
    }

    public function validarUsuario($datos)
    {
        $usuario = ModeloUsuario::obtenerPorEmail($datos["email"]);

        if ($usuario && password_verify($datos["password"], $usuario['password'])) {
            $json = array(
                "status" => 200,
                "id" => $usuario['id'],
                "email" => $usuario['email'],
                "password" => $usuario['password'],
                "id_cliente" => $usuario['id_cliente'],
                "llave_secreta" => $usuario['llave_secreta']
            );
            echo json_encode($json, true);
        } else {
            $json = array(
                "status" => 404,
                "detalle" => "Usuario no encontrado o contraseña incorrecta"
            );
            echo json_encode($json, true);
        }
    }
}
?>
