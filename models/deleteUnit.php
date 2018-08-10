<?php

//header('Content-type: application/json; charset=UTF-8');

$response = array();

if ($_POST['unitId']){
    require_once 'conexion.php';
    $idU = $_POST["unitId"];
    $statement = Conexion::conectar()->prepare("DELETE FROM units WHERE id = :id");
    $statement->bindParam(":id", $idU, PDO::PARAM_INT);
    if ($statement->execute()){
        $response['status'] = 'success';
        $response['message'] = 'Unit deleted successfully.';
    } else{
        $response['status'] = 'error';
        $response['message'] = 'Unable to delete unit.';
    }
    echo json_encode($response);
}