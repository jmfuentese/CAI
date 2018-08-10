<?php

    //header('Content-type: application/json; charset=UTF-8');

    $response = array();

    if ($_POST['teacherId']){
        require_once 'conexion.php';

        $statement = Conexion::conectar()->prepare("DELETE FROM users WHERE id = :id");
        $statement->bindParam(":id", $_POST['teacherId'], PDO::PARAM_INT);
        if ($statement->execute()){
            $response['status'] = 'success';
            $response['message'] = 'Teacher deleted successfully.';
        } else{
            $response['status'] = 'error';
            $response['message'] = 'Unable to delete teacher.';
        }
        echo json_encode($response);
    }