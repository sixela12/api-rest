<?php


    class ControladorCitas{

        public function index(){

            $pacientes=ModeloCitas::getCitasDetalladas();
            $json = array(
    
                "detalle"=>$pacientes,
            
            
            
            );
            
            echo json_encode($json, true);
            
            return;

            
            
        }

        public function create($datos){


            if(isset($datos["Nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $datos["Nombre"])){


                $json = array(
    
                    "detalle"=>"Eerror en el campo del nombre permitido"
                
                
                
                );
                
                echo json_encode($json, true);
                
                return;



            }


        $pacientes=ModeloCitas::index("Citas");

          
        

            $create = ModeloCitas::create("citas", $datos);

            if($create == "ok"){

               $json = array(


                "status" => 200,
                "detalle" => "Registro exitoso, ha sido guardado"



               );

               echo json_encode($json, true);
               return;
            }
    
            



        
    }

        public function update($id){
            $json = array(
    
                "detalle"=>"Paciente Actualizado".$id
            
            
            
            );
            
            echo json_encode($json, true);
            
            return;
        }

        public function show($id){
            $json = array(
    
                "detalle"=>"Estas en la vista Create".$id
            
            
            
            );
            
            echo json_encode($json, true);
            
            return;
        }

        public function delete($id){
            $json = array(
    
                "detalle"=>"Sea eliminado".$id
            
            
            
            );
            
            echo json_encode($json, true);
            
            return;
        }

        


    }


?>