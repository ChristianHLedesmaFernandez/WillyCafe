<?php 

class ControladorConsumos{
	// Mostrar Consumos
	static public function ctrMostrarConsumos($item, $valor){
		$respuesta = ModeloConsumos:: mdlMostrarConsumos($item, $valor);
		return $respuesta;
	}
	// Rango de Fecha
	static function ctrMostrarRangoFechasConsumos($fechaInicial, $fechaFinal){
		$respuesta = ModeloConsumos::mdlMostrarRangoFechasConsumos($fechaInicial, $fechaFinal);
		return $respuesta;
	}
	// Crear un Consumo
	static public function ctrCrearConsumo(){
		if(isset($_POST["nuevaVenta"])){
			// Recibo las variables
			$codigo = $_POST["nuevaVenta"];
			$cliente = $_POST["seleccionarCliente"];
			$vendedor = $_POST["idVendedor"];
			$productos = $_POST["listaProductos"];
			$descuento = $_POST["nuevoPrecioDescuento"];
			$neto = $_POST["nuevoPrecioNeto"];
			$total = $_POST["totalVenta"];
			$metodoPago = $_POST["nuevoMetodoPago"];
			//
			/*
			printf("aca vemos lo que viene desde crear consumo");
			printf($codigo);
			printf($cliente);
			printf($vendedor);
			printf($descuento);
			printf($neto);
			printf($total);
			printf($metodoPago);
			*/
			//
			// Fin Recibir
			
			if(!empty($productos)){ // Verifico si la lista no este vacia
				// Actualizar las compras del cliente, reducir Stock y aumentar las ventas de los productos
				$listaProductos = json_decode($productos, true);
				$totalProductosComprados =  array(); // Para calcular la cantidad de productos comprados por el cliente.
				foreach ($listaProductos as $key => $value){
					array_push($totalProductosComprados, $value["cantidad"]);				
					$itemProducto = "id";
					$valorProducto = $value["id"];
					$orden = "id";
					$traerProducto = ModeloProductos::mdlMostrarProductos($itemProducto, $valorProducto, $orden);			
					// Actualizar Ventas
					$itemVentas = "ventas";
					$valorVentas = $value["cantidad"] + $traerProducto["ventas"];
					$nuevaVenta = ModeloProductos::mdlActualizarProducto($itemVentas, $valorVentas, $valorProducto);
					// Actualizar Stock
					$itemStock = "stock";
					$valorStock = $value["stock"];
					$nuevaVenta = ModeloProductos::mdlActualizarProducto($itemStock, $valorStock, $valorProducto);
				}			
				$itemCliente = "id_user";
				$valorCliente = $cliente;
				$traerCuentaCliente = ModeloCuentas::mdlMostrarCuentas($itemCliente, $valorCliente);
				// Actualizar cantidad de compras del cliente
				$itemCompras = "compras";
				$valorCompras = $traerCuentaCliente["compras"] + array_sum($totalProductosComprados);
				$comprasCliente = ModeloCuentas::mdlActualizarCuenta($itemCompras, $valorCompras, $valorCliente);
				// Fin Actualizar Compras, reducir Stock y aumentar las ventas			
				// Actualizar saldo de la cuenta del Cliente			
				if($metodoPago == "CuentaCorriente"){
					$itemSaldoActual = "saldo_actual";
					$valorSaldoActual = $traerCuentaCliente["saldo_actual"] + $total;
					$comprasCliente = ModeloCuentas::mdlActualizarCuenta($itemSaldoActual, $valorSaldoActual, $valorCliente);
				}
				// Fin Actualizar Saldo 
				// Actualizar fecha de ultima compra del Cliente			
				date_default_timezone_set('America/Argentina/Buenos_Aires');
				$fecha = date("Y-m-d");
				$hora = date("H:i:s");
				$itemUltimaCompra = "ultima_compra";
				$valorUltimaCompra = $fecha .' '. $hora;
				$comprasCliente = ModeloCuentas::mdlActualizarCuenta($itemUltimaCompra, $valorUltimaCompra, $valorCliente);
				// Fin Actualizar Fecha ultima compra
				// Guardar el Consumo
				$datos = array("id_ven" 		=> $vendedor,
							   "id_cli" 		=> $cliente,
							   "codigo" 		=> $codigo,
							   "productos" 		=> $productos,
							   "total_descuento"=> $descuento,
							   "neto" 			=> $neto,
							   "total" 			=> $total,
							   "metodo_pago" 	=> $metodoPago);
				$respuesta = ModeloConsumos::mdlIngresarConsumo($datos);
				if($respuesta){
					echo'<script>
							swal({
							  type: "success",
							  title: "El consumo ha sido guardado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
									if (result.value) {
										window.location = "adminconsumos";
									}
								})
						</script>';
				}
			}		
		}		
	}
	// Editar un Consumo
	static public function ctrEditarConsumo(){
		if(isset($_POST["editarConsumo"])){
			// Recibo las variables
			$codigo = $_POST["editarConsumo"];
			$idConsumo = $_POST["idConsumo"];
			$clienteAnt = $cliente = $_POST["seleccionarCliente"];
			$vendedor = $_POST["idVendedor"];
			$productos = $_POST["listaProductos"];
			$descuento = $_POST["nuevoPrecioDescuento"];
			$neto = $_POST["nuevoPrecioNeto"];
			$total = $_POST["totalVenta"];
			$metodoPago = $_POST["nuevoMetodoPago"];
			// Fin Recibir
			// Banderas
			$cambioProducto = true;
			$cambioMetodoPago = true;
			// Fin Banderas
			// Traer la tabla de Consumo
			$itemConsumo = "id_con";
			$valorConsumo = $idConsumo;			
			$traerConsumo = ModeloConsumos::mdlMostrarConsumos($itemConsumo, $valorConsumo);		
			$productosAnt = $traerConsumo["productos"];
			$metodoPagoAnt = $traerConsumo["metodo_pago"];
			$totalAnt = $traerConsumo["total"];
			// Verificar Cambios
			if(empty($productos)){  //($productos == "") { 
				$productos = $traerConsumo["productos"];
				$cambioProducto = false;
			}
			if($metodoPago == $metodoPagoAnt){
				$cambioMetodoPago = false;
			}
			// Fin Verificar Cambios
			// Formatear las tablas de cuenta de cliente y productos  para volver los valores originales
			if($cambioProducto){
				$listaProductos = json_decode($productosAnt, true);
				$totalProductosCompradosAnt = array();
				// Formatear la tabla de productos
				foreach ($listaProductos as $key => $value) {
					array_push($totalProductosCompradosAnt, $value["cantidad"]);
					$itemProductoAnt = "id";
					$idProductoAnt = $value["id"];
					$orden = "id";
					$traerProductoAnt = ModeloProductos::mdlMostrarProductos($itemProductoAnt, $idProductoAnt, $orden);
					// Actualizar el campo ventas en Tabla Productos
					$itemVentasAnt = "ventas";											
					$valorVentasAnt = $traerProductoAnt["ventas"] - $value["cantidad"];	
					$VentasAnt = ModeloProductos::mdlActualizarProducto($itemVentasAnt, $valorVentasAnt, $idProductoAnt);
					//Actualizar Stock
					$itemStockAnt = "stock";
					$valorStockAnt = $traerProductoAnt["stock"] + $value["cantidad"];
					$VentasAnt = ModeloProductos::mdlActualizarProducto($itemStockAnt, $valorStockAnt, $idProductoAnt);
				}
				$sumaProductoCompradosAnt = array_sum($totalProductosCompradosAnt);
				// Formatear tabla Cuenta de cliente				
				$itemClienteAnt = "id_user";
				$valorClienteAnt = $clienteAnt;
				$traerCuentaClienteAnt = ModeloCuentas::mdlMostrarCuentas($itemClienteAnt, $valorClienteAnt);
				// Formatear cantidad de compras del cliente
				$itemCuentaAnt = "compras";
				$valorCuentaAnt = $traerCuentaClienteAnt["compras"] - array_sum($totalProductosCompradosAnt);
				$compraClienteAnt = ModeloCuentas::mdlActualizarCuenta($itemCuentaAnt, $valorCuentaAnt, $valorClienteAnt);	
				// Formatear Saldo de la cuenta del Cliente
				if($metodoPagoAnt == "CuentaCorriente"){
					$itemSaldoAnt = "saldo_actual";
					$valorSaldoAnt = $traerCuentaClienteAnt["saldo_actual"] - $totalAnt;
					$compraClienteAnt = ModeloCuentas::mdlActualizarCuenta($itemSaldoAnt, $valorSaldoAnt, $valorClienteAnt);
				} 
				// Fin Formatear 	
				// Actualizar nuevamente las compras del cliente y reducir el stock y aumentar las ventas de los productos
				$listaProductos = json_decode($productos, true);
				$totalProductosComprados =  array();
				foreach ($listaProductos as $key => $value){
					array_push($totalProductosComprados, $value["cantidad"]);				
					$itemProducto = "id";
					$valorProducto = $value["id"];
					$orden = "id";
					$traerProducto = ModeloProductos::mdlMostrarProductos($itemProducto, $valorProducto, $orden);
					// Actualizar Ventas
					$itemVentas = "ventas";
					$valorVentas = $value["cantidad"] + $traerProducto["ventas"];
					$nuevaVentas = ModeloProductos::mdlActualizarProducto($itemVentas, $valorVentas, $valorProducto);			
					// Actualizar Stock
					$itemStock = "stock";
					$valorStock = $traerProducto["stock"] - $value["cantidad"];
					$nuevaVentas = ModeloProductos::mdlActualizarProducto($itemStock, $valorStock, $valorProducto);
				}				
				// Actualizar nuevamente la cuenta del Cliente
				$itemCliente = "id_user";
				$valorCliente = $cliente;
				$traerCuentaCliente = ModeloCuentas::mdlMostrarCuentas($itemCliente, $valorCliente);
				// Actualizar cantidad de compras del cliente
				$itemCompras = "compras";
				$valorCompras = $traerCuentaCliente["compras"] + array_sum($totalProductosComprados);
				$comprasCliente = ModeloCuentas::mdlActualizarCuenta($itemCompras, $valorCompras, $valorCliente);
				// Fin Actualizar Compras, reducir Stock y aumentar las ventas			
				// Actualizar saldo de la cuenta del Cliente		
				if($metodoPago == "CuentaCorriente"){
					$itemSaldoActual = "saldo_actual";
					$valorSaldoActual = $traerCuentaCliente["saldo_actual"] + $total;
					$comprasCliente = ModeloCuentas::mdlActualizarCuenta($itemSaldoActual, $valorSaldoActual, $valorCliente);
				}
				// Fin Actualizar Saldo	
			} elseif($cambioMetodoPago){				
				$totalProductosComprados =  array();
				$listaProductos = json_decode($productos, true);
				foreach ( $listaProductos as $key => $value){
					array_push($totalProductosComprados, $value["cantidad"]);
				}
				// Formatear tabla Cuenta de cliente
				$itemClienteAnt = "id_user";
				$valorClienteAnt = $clienteAnt;
				$traerCuentaClienteAnt = ModeloCuentas::mdlMostrarCuentas($itemClienteAnt, $valorClienteAnt);
				if($metodoPagoAnt == "CuentaCorriente"){					
					// Formatear Saldo de la cuenta del Cliente				
					$itemSaldoAnt = "saldo_actual";
					$valorSaldoAnt = $traerCuentaClienteAnt["saldo_actual"] - $totalAnt;
					$compraClienteAnt = ModeloCuentas::mdlActualizarCuenta($itemSaldoAnt, $valorSaldoAnt, $valorClienteAnt);
					// Fin Formatear
				}
				// Actualizar la tabla cuenta con los datos nuevos				
				$itemCliente = "id_user";
				$valorCliente = $cliente;
				$traerCuentaCliente = ModeloCuentas::mdlMostrarCuentas($itemCliente, $valorCliente);
				if($metodoPago == "CuentaCorriente"){					
					$itemSaldoActual = "saldo_actual";
					$valorSaldoActual = $traerCuentaCliente["saldo_actual"] + $total;
					$comprasCliente = ModeloCuentas::mdlActualizarCuenta($itemSaldoActual, $valorSaldoActual, $valorCliente);
				}		
			}						
			// Editar el Consumo			
			$datos = array("id_ven" 		=> $vendedor,
						   "id_cli" 		=> $cliente,
						   "codigo" 		=> $codigo,
						   "productos" 		=> $productos,
						   "total_descuento"=> $descuento,
						   "neto" 			=> $neto,
						   "total" 			=> $total,
						   "metodo_pago" 	=> $metodoPago);
			$respuesta = ModeloConsumos::mdlEditarConsumo($datos);
			if($respuesta){
				echo'<script>
						swal({
						  type: "success",
						  title: "El consumo ha sido Editado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
								if (result.value) {
									window.location = "adminconsumos";
								}
							})
					</script>';
			}
		}		
	}
	// Eliminar un Consumo
	static public function ctrEliminarConsumo(){
		if(isset($_GET["idConsumo"])){
			$item = "id_con";
			$valor = $_GET["idConsumo"];
			$traerConsumo = ModeloConsumos::mdlMostrarConsumos($item, $valor);
			$idCliente = $traerConsumo["id_cli"];
			$productos = $traerConsumo["productos"];
			$total = $traerConsumo["total"];
			$metodoPago = $traerConsumo["metodo_pago"];
			// Actualizar Fecha ultima Compra en Tabla Cuenta
			$itemConsumos = NULL;
			$valorConsumos = NULL;
			$traerConsumos = ModeloConsumos::mdlMostrarConsumos($itemConsumos, $valorConsumos);	
			$guardarFechas = array();			
			// Formatear tabla de Productos y Cuenta segun Clientes
			$listaProductos = json_decode($productos, true);
			$totalProductosCompradosAnt = array();
			// Formatear la tabla de productos			
			foreach ($listaProductos as $key => $value) {
				array_push($totalProductosCompradosAnt, $value["cantidad"]);
				$itemProductoAnt = "id";
				$valorProductoAnt = $value["id"];
				$orden = "id";
				$traerProductoAnt = ModeloProductos::mdlMostrarProductos($itemProductoAnt, $valorProductoAnt, $orden);
				// Actualizar Ventas
				$itemVentasAnt = "ventas";											
				$valorVentasAnt = $traerProductoAnt["ventas"] - $value["cantidad"];
				$VentasAnt = ModeloProductos::mdlActualizarProducto($itemVentasAnt, $valorVentasAnt, $valorProductoAnt);
				//Actualizar Stock
				$itemStockAnt = "stock";
				$valorStockAnt = $traerProductoAnt["stock"] + $value["cantidad"];
				$VentasAnt = ModeloProductos::mdlActualizarProducto($itemStockAnt, $valorStockAnt, $valorProductoAnt);
			}
			// Formatear tabla Cuenta de cliente
			$itemClienteAnt = "id_user";
			$valorClienteAnt = $idCliente;
			$traerCuentaClienteAnt = ModeloCuentas::mdlMostrarCuentas($itemClienteAnt, $valorClienteAnt);			
			// Formatear cantidad de compras del cliente
			$itemCuentaAnt = "compras";
			$valorCuentaAnt = $traerCuentaClienteAnt["compras"] - array_sum($totalProductosCompradosAnt);					
			$compraClienteAnt = ModeloCuentas::mdlActualizarCuenta($itemCuentaAnt, $valorCuentaAnt, $valorClienteAnt);
			// Formatear Saldo de la cuenta del Cliente
			if($metodoPago == "CuentaCorriente"){
				$itemSaldoAnt = "saldo_actual";
				$valorSaldoAnt = $traerCuentaClienteAnt["saldo_actual"] - $total;
				$compraClienteAnt = ModeloCuentas::mdlActualizarCuenta($itemSaldoAnt, $valorSaldoAnt, $valorClienteAnt);
			}
			foreach ($traerConsumos as $key => $value) {
				if($value["id_cli"] == $idCliente){
					array_push($guardarFechas, $value["fecha"]);
				}
			}			
			if(count($guardarFechas) > 1){				
				if($traerConsumo["fecha"] > $guardarFechas[count($guardarFechas)-2]){ // para tomar el penultimo indice
					$fechaUltimaCompra = $guardarFechas[count($guardarFechas)-2];
				}else{
					$fechaUltimaCompra = $guardarFechas[count($guardarFechas)-1];
				}
			}else{
				$fechaUltimaCompra = "0000-00-00 00:00:00";
			}
			$itemCuenta = "ultima_compra";
			$comprasCliente = ModeloCuentas::mdlActualizarCuenta($itemCuenta, $fechaUltimaCompra, $idCliente);
			//---------------------------------------------------------------------------------------------------
			// Eliminar Consumo
			//$respuesta = true;
			$respuesta = ModeloConsumos::mdlBorrarConsumo($valor);
			$respuesta = true;			
			if($respuesta){
				echo'<script>
						swal({
						  type: "success",
						  title: "El Consumo ha sido borrado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
								if (result.value) {
									window.location = "adminconsumos";
								}
							})
					</script>';
			}
		}
	}
	// Descargar Reportes
	static public function ctrDescargarReporte(){
		if(isset($_GET['reporte'])){
			if(isset($_GET['fechaInicial']) && isset($_GET['fechaFinal'])){
				$consumos = ModeloConsumos::mdlMostrarRangoFechasConsumos($_GET['fechaInicial'], $_GET['fechaFinal']);
			}else{
				$item = NULL;
				$valor = NULL;
				$consumos = ModeloConsumos::mdlMostrarConsumos($item, $valor);
			}
			// Crear el archivo de Excel
			$Name = $_GET['reporte'].'_willycafe.xls';
			// Cabecera
			header('Expires: 0');
			header('Cache-control: private');
			header('Content-type: application/vnd.ms-excel'); // Archivo de Excel.
			header('Cache-Control: cache, must-revalidate');
			header('Content-Description: File Transfer');
			header('Last-Modified: '. date('D, d M Y H:i:s'));
			header('Pragma: public');
			header('Content-Disposition:; filename= "'. $Name .'"');
			header('Content-Transfer-Encoding: binary');
			// Fin Cabecera
			echo utf8_decode("
				<table border='0'>					
					<tr> 
						<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
						<td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
						<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
						<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
						<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
						<td style='font-weight:bold; border:1px solid #eee;'>DESCUENTO</td>
						<td style='font-weight:bold; border:1px solid #eee;'>NETO</td>		
						<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
						<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td	
						<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");
			foreach ($consumos as $row => $item) {
				echo utf8_decode("
					<tr>
						<td style='border:1px solid #eee; vertical-align:middle; text-align: center;'>". $item["codigo"] ."</td>
						<td style='border:1px solid #eee; vertical-align:middle;'>". $item["cliente"] ."</td> 
						<td style='border:1px solid #eee; vertical-align:middle;'>". $item["vendedor"] ."</td>
						<td style='border:1px solid #eee; text-align: center;'>
					");
				$productos = json_decode($item["productos"], true);
				foreach ($productos as $key => $valueProductos) {						
						echo utf8_decode($valueProductos["cantidad"]."<br>");
				}
				echo utf8_decode("</td> 
						<td style='border:1px solid #eee;'>
					");
				foreach ($productos as $key => $valueProductos) {						
						echo utf8_decode($valueProductos["descripcion"]."<br>");
				}
				echo utf8_decode("</td>
						<td style='border:1px solid #eee; vertical-align:middle; text-align: right;'>". number_format($item["total_descuento"], 2) ."</td>
						<td style='border:1px solid #eee; vertical-align:middle; text-align: right;'>". number_format($item["neto"], 2) ."</td>
						<td style='border:1px solid #eee; vertical-align:middle; text-align: right;'>". number_format($item["total"], 2) ."</td>
						<td style='border:1px solid #eee; vertical-align:middle; text-align: center;'>". $item["metodo_pago"] ."</td>
						<td style='border:1px solid #eee; vertical-align:middle; text-align: center;'>". substr($item["fecha"], 0, 10) ."</td>
					</tr>
					");	
			}
			echo "	
				</table>";
		}
	}
	// Suma el total de consumos
	static public function ctrSumaTotalConsumos($item, $valor){
		$respuesta = ModeloConsumos::mdlSumaTotalConsumos($item, $valor);
		return $respuesta;
	}

}