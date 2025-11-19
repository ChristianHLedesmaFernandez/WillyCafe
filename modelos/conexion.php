<?php

require_once __DIR__ . '/../config/config.php';
class Conexion{
	public static function conectar(){
		try {
		    //$link = new PDO("mysql:host=localhost;dbname=".DB, 
			$link = new PDO("mysql:host=".HOST.";dbname=".DB,
						USER, 
						PASSWORD);
		$link ->exec("set names utf8");
		return $link;
		} catch (PDOException $e) {
		    //print "¡Error!: " . $e->getMessage() . "<br/>";
		    print "<h1>¡Error!: Algo anda mal. Intente conectarse mas tarde</h1> <br/>";
		    die();
		}
	}
}