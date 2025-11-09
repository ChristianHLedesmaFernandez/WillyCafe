<?php 

require_once "conexion.php";

class ModeloConsumos{
	// Mostrar Consumos
	static public function mdlMostrarConsumos($item, $valor){
		if(!empty($item)){
			//$stmt =Conexion::conectar()->prepare("SELECT * FROM consumos WHERE $item = :$item ORDER BY fecha DESC");
			$stmt =Conexion::conectar()->prepare("SELECT consumos.*, t_cliente.nombre AS cliente, t_vendedor.nombre AS vendedor FROM consumos INNER JOIN usuarios AS t_cliente ON id_cli = t_cliente.id INNER JOIN usuarios AS t_vendedor ON id_ven = t_vendedor.id WHERE $item = :$item ORDER BY id_con ASC");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			//$stmt = Conexion::conectar()->prepare("SELECT * FROM consumos ORDER BY fecha DESC" );
			$stmt = Conexion::conectar()->prepare("SELECT consumos.*, t_cliente.nombre AS cliente, t_vendedor.nombre AS vendedor FROM consumos INNER JOIN usuarios AS t_cliente ON id_cli = t_cliente.id INNER JOIN usuarios AS t_vendedor ON id_ven = t_vendedor.id ORDER BY id_con ASC");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}		
		$stmt -> close();
		$stmt = NULL;
	}
	// Mostrar Rango de Fecha Consumos
	static public function mdlMostrarRangoFechasConsumos($fechaInicial, $fechaFinal){
		if($fechaInicial == null){
			$stmt = Conexion::conectar()->prepare("SELECT consumos.*, t_cliente.nombre AS cliente, t_vendedor.nombre AS vendedor FROM consumos INNER JOIN usuarios AS t_cliente ON id_cli = t_cliente.id INNER JOIN usuarios AS t_vendedor ON id_ven = t_vendedor.id ORDER BY id_con ASC");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}else if($fechaInicial == $fechaFinal){

			$stmt =Conexion::conectar()->prepare("SELECT consumos.*, t_cliente.nombre AS cliente, t_vendedor.nombre AS vendedor FROM consumos INNER JOIN usuarios AS t_cliente ON id_cli = t_cliente.id INNER JOIN usuarios AS t_vendedor ON id_ven = t_vendedor.id WHERE fecha like '%$fechaFinal%'");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetchAll();

		}else{
			$fechaActual = new DateTime();
			$fechaActual -> add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual -> format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 -> add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2 -> format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){
				$stmt = Conexion::conectar()->prepare("SELECT consumos.*, t_cliente.nombre AS cliente, t_vendedor.nombre AS vendedor FROM consumos INNER JOIN usuarios AS t_cliente ON id_cli = t_cliente.id INNER JOIN usuarios AS t_vendedor ON id_ven = t_vendedor.id WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else {
				$stmt = Conexion::conectar()->prepare("SELECT consumos.*, t_cliente.nombre AS cliente, t_vendedor.nombre AS vendedor FROM consumos INNER JOIN usuarios AS t_cliente ON id_cli = t_cliente.id INNER JOIN usuarios AS t_vendedor ON id_ven = t_vendedor.id WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal'");
			}
			$stmt -> execute();
			return $stmt -> fetchAll();

		}
	} 

	// Registrar un Consumo
	static public function mdlIngresarConsumo($datos){
		$stmt = Conexion::conectar()->prepare("INSERT INTO consumos(codigo, id_cli, id_ven, productos, total_descuento, neto, total, metodo_pago) VALUES (:codigo, :id_cli, :id_ven, :productos, :total_descuento, :neto, :total, :metodo_pago)");
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_cli", $datos["id_cli"], PDO::PARAM_INT);
		$stmt->bindParam(":id_ven", $datos["id_ven"], PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":total_descuento", $datos["total_descuento"], PDO::PARAM_STR);
		$stmt->bindParam(":neto", $datos["neto"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
		if($stmt->execute()){
			return TRUE;
		}else{
			return FALSE;		
		}
		$stmt->close();
		$stmt = NULL;
	}
	// Editar un Consumo
	static public function mdlEditarConsumo($datos){
		$stmt = Conexion::conectar()->prepare("UPDATE consumos SET id_cli = :id_cli, id_ven = :id_ven, productos = :productos, total_descuento = :total_descuento, neto = :neto, total = :total, metodo_pago = :metodo_pago WHERE codigo = :codigo");
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_cli", $datos["id_cli"], PDO::PARAM_INT);
		$stmt->bindParam(":id_ven", $datos["id_ven"], PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":total_descuento", $datos["total_descuento"], PDO::PARAM_STR);
		$stmt->bindParam(":neto", $datos["neto"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
		if($stmt->execute()){
			return TRUE;
		}else{
			return FALSE;		
		}
		$stmt->close();
		$stmt = NULL;
	}
	// Borrar un Consumo
	static public function mdlBorrarConsumo($datos){
		$stmt = Conexion::conectar()->prepare("DELETE FROM consumos WHERE id_con = :id");
		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);
		if($stmt -> execute()){
			return true;		
		}else{
			return false;
		}
		$stmt -> close();
		$stmt = NULL;		
	}	
	// Sumar el Total Ventas
	static public function mdlSumaTotalConsumos($item, $valor){
		if(!empty($item)){
			$stmt =Conexion::conectar()->prepare("SELECT SUM(neto) as total FROM consumos WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			
		}else{
			$stmt =Conexion::conectar()->prepare("SELECT SUM(neto) as total FROM consumos");
			
		}
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = NULL;		
	}
}
