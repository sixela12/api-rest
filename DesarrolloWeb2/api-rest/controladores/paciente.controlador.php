<?php
    class ControladorPacientes{

        public function index(){

        $usuario = ModeloUsuario::index("usuario");
         if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

        foreach ($usuario as $key => $value) {
  
  
          if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
             $value->id_cliente .":". $value->llave_secreta){


                
  
            $pacientes=ModeloPacientes::index("Pacientes", null, null);
            $json = array(
    
                "detalle"=>$pacientes,
            
            
            
            );
            
            echo json_encode($json, true);
            
            return;
        }   // Manejo del caso cuando la autenticación falla
        http_response_code(401); // Unauthorized
        echo json_encode(array("error" => "Autenticación fallida"));
    }}}


        public function create($datos){

            $usuario = ModeloUsuario::index("usuario");
         if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

        foreach ($usuario as $key => $value) {
  
  
          if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
             $value->id_cliente .":". $value->llave_secreta){

                if(isset($datos["Nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $datos["Nombre"])){


                    $json = array(
        
                        "detalle"=>"Eerror en el campo del nombre permitido"
                    
                    
                    
                    );
                    
                    echo json_encode($json, true);
                    
                    return;
    
    
    
                }

                $pacientes=ModeloPacientes::index("Pacientes", null, null);

          foreach ($pacientes as $key => $value) {

            if($value->Nombre == $datos["Nombre"]){

							$json = array(

								"status"=>404,
								"detalle"=>"El paciente ya existe en la base de datos"

							);

							echo json_encode($json, true);	

							return;




						}


                        $create = ModeloPacientes::create("pacientes", $datos);

            if($create == "ok"){

               $json = array(


                "status" => 200,
                "detalle" => "Registro exitoso, pacientes a sido actualizado "



               );

               echo json_encode($json, true);
               return;
            }
            


        }


  
            







        }else{
            // Manejo del caso cuando la autenticación falla
      http_response_code(401); // Unauthorized
      echo json_encode(array("error" => "Autenticación fallida"));

      }
    }}





        
    }


    public function show($id){

        $usuario = ModeloUsuario::index("usuario");
         if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

        foreach ($usuario as $key => $value) {
  
  
          if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
             $value->id_cliente .":". $value->llave_secreta){
  
            $pacientes = ModeloPacientes::show("pacientes", $id);

            $json = array(

    
                "status"=>200,
                "detalle"=>$pacientes,
            
            
            
            );
            
            echo json_encode($json, true);
            
            return;
        }



    }}

    }

    public function update($id, $datos){


 $usuario = ModeloUsuario::index("usuario");
         if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

        foreach ($usuario as $key => $value) {
  
  
          if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
             $value->id_cliente .":". $value->llave_secreta){



                foreach ($datos as $key => $valueDatos) {

                    if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){

                        $json = array(

                            "status"=>404,
                            "detalle"=>"Error en el campo ".$key

                        );

                        echo json_encode($json, true);

                        return;
                    }

                }

                $datos = array( "Id"=>$id,
											      "Nombre"=>$datos["Nombre"],
											      "Telefono"=>$datos["Telefono"],
											      "Dirección"=>$datos["Dirección"]);

                            $update = ModeloPacientes::update("pacientes", $datos);


                            if($update == "ok"){

                                $json = array(
                 
                 
                                 "status" => 200,
                                 "detalle" => "Registro exitoso, su curso ha sido guardado"
                 
                 
                 
                                );
                 
                                echo json_encode($json, true);
                                return;
                             }





             }else{
                // Manejo del caso cuando la autenticación falla
          http_response_code(401); // Unauthorized
          echo json_encode(array("error" => "Autenticación fallida"));
    
          }}}
  





    }

    public function delete($id){

        $pacientes = ModeloUsuario::index("usuario");


        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

            foreach ($pacientes as $key => $value) {
      
      
              if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
                 $value->id_cliente .":". $value->llave_secreta){

                    $pacientes = ModeloPacientes::delete("pacientes", $id);

                    if($pacientes == "ok"){
                        $json = array(
                 
                 
                            "status" => 200,
                            "detalle" => "Eliminacion exitosa"
            
            
            
                           );
                           echo json_encode($json, true);
                                return;
                        

                    }






                    



                 }else{
                    // Manejo del caso cuando la autenticación falla
              http_response_code(401); // Unauthorized
              echo json_encode(array("error" => "Autenticación fallida"));
        
              }}}
    }

    public function index2(){

        $usuario = ModeloPacientes::index("usuario");
         if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

        foreach ($usuario as $key => $value) {
  
  
          if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
             $value->id_cliente .":". $value->llave_secreta){


                
  
            $pacientes=ModeloPacientes::index2("Citas", "Medicos", "Pacientes");
            $json = array(
    
                "detalle"=>$pacientes,
            
            
            
            );
            
            echo json_encode($json, true);
            
            return;
        }
    }}}

    


}
?>