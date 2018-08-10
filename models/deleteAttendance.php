<?php

//header('Content-type: application/json; charset=UTF-8');

$response = array();

if ($_POST['studentId'] ){
    require_once 'conexion.php';

    $mat = $_POST["studentId"];
    $horaActual = $_POST["horaActual"];

    $statement = Conexion::conectar()->prepare("DELETE FROM sessions WHERE id_student = :idS AND end_time IS NULL");
    $statement->bindParam(":idS", $_POST['studentId'], PDO::PARAM_INT);
    $statement->execute();
}