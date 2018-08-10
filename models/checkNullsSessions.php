<?php
/*Funcion que verifica si la hora en la que ingresó esa sesión que no tiene hora, es mayor a cuatro horas a diferencia de la hora
actual, entonces borra la sesión.

NOTA:
Este script se ejecuta periodicamente cada hora haciendo uso de 'CRON JOBS' en el servidor que aloja el sistema
*/
require_once "conexion.php";
$file = dirname(__FILE__) . '/output.txt';

$statement = Conexion::conectar()->prepare("SELECT * FROM sessions WHERE end_time is NULL");
$statement->execute();

$respuesta = $statement->fetchAll();
$horaActual = new DateTime(date("Y-m-d H:i:s"));

foreach ($respuesta as $row => $item) {
    $horaEntrada = new DateTime($item["today_date"] . " " . $item["start_time"]);
    $diferencia = $horaActual->diff($horaEntrada);
    //file_put_contents($file, (string)$horaEntrada . "\n", FILE_APPEND);
    if ($diferencia->h >= 4 || $diferencia->d >= 1 || $diferencia->m >= 1) { //Si la hora de entrada de esa sesion que no tiene hora de salida,
        // es mayor a cuatro horas o mayor a un dia o icluso mayor a un mes, entonces se eliminará la sesion
        //echo "hora eliminada";

        //echo "  Se elimina la hora";
        //echo "IDSTUDENT: ". $item["id_student"];
        //echo "STARTIME: ". $item["start_time"];

        $stmt = Conexion::conectar()->prepare("SELECT * FROM sessions WHERE id_student=:idS AND start_time = :sT");
        $stmt->bindParam(":idS", $item["id_student"], PDO::PARAM_INT);
        $stmt->bindParam(":sT", $item["start_time"], PDO::PARAM_STR);
        $stmt->execute();
        $history = $stmt->fetch();
        $stmtInsert = Conexion::conectar()->prepare("INSERT INTO deleteHistory (id_session, id_student, id_teacher, id_group, date)
                                                    VALUES (:idSe, :idSt, :idT, :idG, :date)");
        $stmtInsert->bindParam(":idSe", $item["id"], PDO::PARAM_INT);
        $stmtInsert->bindParam(":idSt", $item["id_student"], PDO::PARAM_INT);
        $stmtInsert->bindParam(":idT", $item["id_teacher"], PDO::PARAM_INT);
        $stmtInsert->bindParam(":idG", $item["id_group"], PDO::PARAM_INT);
        $stmtInsert->bindParam(":date", $item["today_date"], PDO::PARAM_STR);
        $stmtInsert->execute();
        //echo "<pre>";
        //print_r($history);
        //echo "</pre>";

        $statement2 = Conexion::conectar()->prepare("DELETE FROM sessions WHERE id_student=:idS AND start_time = :sT");
        $statement2->bindParam(":idS", $item["id_student"], PDO::PARAM_INT);
        $statement2->bindParam(":sT", $item["start_time"], PDO::PARAM_STR);
        $statement2->execute();
    }
}




$data = "hello, it's " . date('d/m/Y H:i:s') . "\n";

file_put_contents($file, $data, FILE_APPEND);
?>