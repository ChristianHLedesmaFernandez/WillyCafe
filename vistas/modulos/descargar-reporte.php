<?php 

// Controladores
require_once "../../controladores/consumos.controlador.php";
require_once "../../controladores/clientes.controlador.php";
require_once "../../controladores/usuarios.controlador.php";
// Modelos
require_once "../../modelos/consumos.modelo.php";
require_once "../../modelos/clientes.modelo.php";
require_once "../../modelos/usuarios.modelo.php";


$reporte = new ControladorConsumos();
$reporte -> ctrDescargarReporte();