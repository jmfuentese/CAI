<?php

//header('Content-type: application/json; charset=UTF-8');

$response = array();

if ($_POST['groupId']){
    require_once 'conexion.php';

    $statement = Conexion::conectar()->prepare("DELETE FROM groups WHERE id = :id");
    $statement->bindParam(":id", $_POST['groupId'], PDO::PARAM_INT);
    if ($statement->execute()){
        $response['status'] = 'success';
        $response['message'] = 'Group deleted successfully.';
    } else{
        $response['status'] = 'error';
        $response['message'] = 'Unable to delete group.';
    }
    echo json_encode($response);
}