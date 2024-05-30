<?php

require_once("controladores/vista.controlador.php");
require_once("controladores/paciente.controlador.php");
require_once("controladores/usuario.controlador.php");
require_once("modelos/usuario.modelo.php");


require_once("modelos/pacientes.modelo.php");
require_once("modelos/medicos.modelo.php");
require_once("modelos/citas.modelo.php");
require_once("controladores/citas.controlador.php");
require_once("controladores/medicos.controlador.php");



$rutas = new ControladoresRutas();

$rutas->inicio();

?>


