<?php


    class ControladorMedicos{

        public function index(){

            $usuario = ModeloUsuario::index("usuario");
             if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
    
            foreach ($usuario as $key => $value) {
      
      
              if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
                 $value->id_cliente .":". $value->llave_secreta){
      
                $medicos=ModeloMedicos::index("Medicos");
                $json = array(
        
                    "detalle"=>$medicos,
                
                
                
                );
                
                echo json_encode($json, true);
                
                return;
            }else{
                // Manejo del caso cuando la autenticación falla
          http_response_code(401); // Unauthorized
          echo json_encode(array("error" => "Autenticación fallida"));
    
          }
        }}}

        
       

        public function create($datos){


            $usuario = ModeloUsuario::index("usuario");
            if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
   
           foreach ($usuario as $key => $value) {
     
     
             if($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW'] == 
                $value->id_cliente .":". $value->llave_secreta){
   
                   if(isset($datos["Nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $datos["Nombre"])){
   
   
                       $json = array(
           
                           "detalle"=>"Error en el campo del nombre permitido"
                       
                       
                       
                       );
                       
                       echo json_encode($json, true);
                       
                       return;
       
       
       
                   }
   
                   $pacientes=ModeloMedicos::index("Medicos", null, null);
   
             foreach ($pacientes as $key => $value) {
   
               if($value->Nombre == $datos["Nombre"]){
   
                               $json = array(
   
                                   "status"=>404,
                                   "detalle"=>"El paciente ya existe en la base de datos"
   
                               );
   
                               echo json_encode($json, true);	
   
                               return;
   
   
   
   
                           }
   
   
                           $create = ModeloMedicos::create("medicos", $datos);
   
               if($create == "ok"){
   
                  $json = array(
   
   
                   "status" => 200,
                   "detalle" => "Registro exitoso, su curso ha sido guardado"
   
   
   
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
  
            $medicos = ModeloMedicos::show("medicos", $id);

            $json = array(

    
                "status"=>200,
                "detalle"=>$medicos,
            
            
            
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
                                                         "Nombre"=>$datos["nombre"],
                                                         "Telefono"=>$datos["telefono"],
                                                         "Especialidad"=>$datos["especialidad"]);
       
                                   $update = ModeloMedicos::update("medicos", $datos);
       
       
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
    
                        $pacientes = ModeloMedicos::delete("medicos", $id);
    
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

        


    }


?>