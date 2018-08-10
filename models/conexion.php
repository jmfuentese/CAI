<?php

class Conexion{
	public static function conectar(){
		$link = new PDO("mysql:host=localhost;dbname=cai","root","SANCHEZ55");
		//Modificar contraseña, dbname y host en caso de ser necesario por los datos del servicio local
		return $link;
	}
}
?>
