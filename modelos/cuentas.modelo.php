x<?php 

require_once "conexion.php";


class ModeloCuentas{
	// Mostrar Cuentas
	static public function mdlMostrarCuentas($item, $valor){
		if(!empty($item)){
			$stmt =Conexion::conectar()->prepare("SELECT * FROM cuentas WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = Conexion::conectar()->prepare("SELECT * FROM cuentas");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = NULL;
	}
	// Actualizar un Campo de una Cuenta
	static public function mdlActualizarCuenta($item, $valor1, $valor){
		$stmt = Conexion::conectar()->prepare("UPDATE cuentas SET $item = :$item WHERE id_user = :id_user");
		$stmt -> bindParam(":".$item, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id_user", $valor, PDO::PARAM_STR);		
		if($stmt -> execute()){
			return TRUE;
		}else{			
			return FALSE;
		}
		$stmt -> close();		
		$stmt = null;
	}
	// Crear Cuenta
	static public function mdlCrearCuenta($datos){
		$stmt = Conexion::conectar()->prepare("INSERT INTO cuentas (id_user, descuento, compras, ultima_compra, saldo_actual, saldo_anterior) VALUES (:id_user, :descuento, :compras, :ultima_compra, :saldo_actual,
		 :saldo_anterior)");
		$stmt -> bindParam(":id_user", $datos["id"], PDO::PARAM_INT);
		$stmt -> bindParam(":descuento", $datos["descuento"], PDO::PARAM_STR);
		$stmt -> bindParam(":compras", $datos["compras"], PDO::PARAM_STR);
		$stmt -> bindParam(":ultima_compra", $datos["ultimaCompra"], PDO::PARAM_STR);
		$stmt -> bindParam(":saldo_actual", $datos["saldoActual"], PDO::PARAM_STR);
		$stmt -> bindParam(":saldo_anterior", $datos["saldoAnterior"], PDO::PARAM_STR);

		if($stmt -> execute()){
			return TRUE;
		}else{			
			return FALSE;
		}
		$stmt -> close();		
		$stmt = null;

	}

}