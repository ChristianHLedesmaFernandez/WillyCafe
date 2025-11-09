<?php
require_once "../../../config/config.php";
require_once "../../../funcs/funcs.php";

require_once "../../../controladores/consumos.controlador.php";
require_once "../../../modelos/consumos.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";


/*
*/


// Clase Imprimir Factura
class imprimirFactura{

public $codigo;
// Metodo que trae los elementos
public function traerImpresionFactura(){
// Traemos la Informacion del Consumo
$itemConsumo = "codigo";
$valorConsumo = $this->codigo;
$respuestaConsumo = ControladorConsumos::ctrMostrarConsumos($itemConsumo, $valorConsumo);

$fecha =  substr($respuestaConsumo["fecha"], 0, 10);
$productos = json_decode($respuestaConsumo["productos"], true);
//
//var_dump($productos[]);
//

$neto = number_format($respuestaConsumo["neto"], 2);
$metodoPago = $respuestaConsumo["metodo_pago"];
$descuento = number_format($respuestaConsumo["total_descuento"], 2);
$total = number_format($respuestaConsumo["total"], 2);
// Traemos la Informacion de la Cliente
$itemCliente = "id";
$valorCliente =  $respuestaConsumo["id_cli"];

$respuestaCliente = ControladorClientes::ctrMostrarUsuarios($itemCliente, $valorCliente);
$cliente = $respuestaCliente["nombre"] . " " . $respuestaCliente["apellido"] ;

// Traemos la Informacion de la Vendedor
$itemVendedor = "id";
$valorVendedor = $respuestaConsumo["id_ven"];

$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor)[0];
$vendedor = $respuestaVendedor["nombre"];


// Incluir la biblioteca TCPDF principal (busque la ruta de instalación).
require_once('tcpdf_include.php');

// Crear un nuevo Documento PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Para tener Varias paginas en el Documento PDF
$pdf->startPageGroup();
// Agregamos la nueva pagina
$pdf->AddPage();

// Maquetar el Primer Bloque (Encabezado)
$bloque1 = <<<EOF
	
	<table>
		<tr>
		<td style="width:150px"><img src="images/logo-willycafe.png"></td>
		<td style="background-color:white; width:140px">
			<div style="font-size:8.5px; text-align:right; line-height:15px;">
				<br>
				Cuil: 20 - 92632874 - 6
				<br>
				Direccion: Warnes 2000
			</div>
		</td>
		<td style="background-color:white; width:140px">
			<div style="font-size:8.5px; text-align:right; line-height:15px;">
				<br>
				Telefono: 48553971
				<br>
				suaccesorio@hotmail.com
			</div>
		</td>
		<td style="background-color:white; width:110px; text-align:center; color:red">
			<br><br>FACTURA Nº <br>
			$valorConsumo
			
		</td>
		
		</tr>
	</table>

EOF;
// Fin Primer Bloque
// Escribir el Bloque 1
$pdf->writeHTML($bloque1, false, false, false, false, '');
//---------------------------------------------------------------------------------------------------
// Maquetar el Segundo Bloque (Datos del Vendedor/Cliente)
$bloque2 = <<<EOF

	<table>
		<tr>
			<td style="width:540px"><img src="images/back.jpg"></td>
		</tr>
	</table>
	
	<table style="font-size:10px; padding:5px 10p;">
		<tr>
			<td style="border:1px solid #666; background-color:white; width:390px">
				Cliente: $cliente
			</td>
			<td style="border:1px solid #666; background-color:white; width:150px; text-align:right">
				Fecha: $fecha
			</td>
		</tr>
		<tr>
			<td style="border:1px solid #666; background-color:white; width:540px">
				Vendedor: $vendedor
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #666; background-color:white; width:540px"></td>
		</tr>
	</table>	

EOF;
// Fin Segundo Bloque
$pdf->writeHTML($bloque2, false, false, false, false, '');
//---------------------------------------------------------------------------------------------------
// Maquetar el Tercer Bloque (Titulos del a Tabla Productos)
$bloque3 = <<<EOF

	<table style="font-size:10px; padding:5px 10p;">		
		<tr>
			<td style="border:1px solid #666; background-color:white; width:260px; text-align:center">Producto</td>
			<td style="border:1px solid #666; background-color:white; width:80px; text-align:center">Cantidad</td>
			<td style="border:1px solid #666; background-color:white; width:100px; text-align:center">Valor Unit.</td>
			<td style="border:1px solid #666; background-color:white; width:100px; text-align:center">Valor Total</td>
		</tr>		
	</table>

EOF;
// Fin Tercer Bloque
$pdf->writeHTML($bloque3, false, false, false, false, '');
//---------------------------------------------------------------------------------------------------
// Recorro el datos Json de Productos
foreach ($productos as $key => $item) {
$precioUnitario = number_format($item["precio"], 2);
$totalProducto = number_format($item["total"], 2);

// Maquetar el Cuarto Bloque (Tabla Productos)
$bloque4 = <<<EOF

	<table style="font-size:10px; padding:5px 10p;">
		<tr>
			<td style="border:1px solid #666; background-color:white; width:260px; text-align:center">
				$item[descripcion]
			</td>
			<td style="border:1px solid #666; background-color:white; width:80px; text-align:center">
				$item[cantidad]
			</td>
			<td style="border:1px solid #666; background-color:white; width:100px; text-align:center">
				$ $precioUnitario
			</td>
			<td style="border:1px solid #666; background-color:white; width:100px; text-align:center">
				$ $totalProducto
			</td>
		</tr>
	</table>

EOF;
// Fin Cuarto Bloque
//---------------------------------------------------------------------------------------------------
$pdf->writeHTML($bloque4, false, false, false, false, '');
}
//---------------------------------------------------------------------------------------------------
// Maquetar el Quinto Bloque (Tabla Productos)
$bloque5 = <<<EOF

	<table style="font-size:10px; padding:5px 10p;">
		<tr>
			<td style="color #333; background-color:white; width:340px; text-align:center"></td>
			<td style="border-bottom:1px solid #666; background-color:white; width:100px; text-align:center"></td>
			<td style="border-bottom:1px solid #666; color:#333; background-color:white; width:100px; text-align:center"></td>
		</tr>
		<tr>
			<td style="border-right:1px solid #666; color #333; background-color:white; width:340px; text-align:center"></td>
			<td style="border:1px solid #666; background-color:white; width:100px; text-align:center">
				Neto:
			</td>
			<td style="border:1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$ $neto
			</td>
		</tr>
		<tr>
			<td style="border-right:1px solid #666; color #333; background-color:white; width:340px; text-align:center"></td>
			<td style="border:1px solid #666; background-color:white; width:100px; text-align:center">
				Descuento:
			</td>
			<td style="border:1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$descuento
			</td>
		</tr>
		<tr>
			<td style="border-right:1px solid #666; color #333; background-color:white; width:340px; text-align:center"></td>
			<td style="border:1px solid #666; background-color:white; width:100px; text-align:center">
				Total:
			</td>
			<td style="border:1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$ $total
			</td>
		</tr>
	</table>

EOF;
// Fin Quinto Bloque
$pdf->writeHTML($bloque5, false, false, false, false, '');
//---------------------------------------------------------------------------------------------------
// Salida del archivo
$pdf -> Output("factura.php");
}


}
// Recibo la Variables GET
$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();

?>