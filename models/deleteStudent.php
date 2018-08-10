<?php

//header('Content-type: application/json; charset=UTF-8');

$response = array();

if ($_POST['studentId']){
    require_once 'conexion.php';
    $idS = $_POST["studentId"];
    $statement = Conexion::conectar()->prepare("DELETE FROM students WHERE id = :id");
    $statement->bindParam(":id", $idS, PDO::PARAM_INT);
    if ($statement->execute()){
        $response['status'] = 'success';
        $response['message'] = 'Student deleted successfully.';
    } else{
        $response['status'] = 'error';
        $response['message'] = 'Unable to delete student.';
    }
    echo json_encode($response);
}