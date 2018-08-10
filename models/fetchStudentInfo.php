<?php

if (isset($_GET["studentId"])) {
    //se incluye el archivo de conexion
    require_once("conexion.php");
    require_once("../controllers/controller.php");
    //se prepara el query para hacer la consulta a la base de datos
    $statement = Conexion::conectar()->prepare("SELECT * FROM students WHERE id = :id");
    $statement->bindParam(":id", $_GET['studentId'], PDO::PARAM_INT);
    //se ejecuta la query
    $statement->execute();
    $studentInfo = $statement->fetch();
    $stmtGroups = Conexion::conectar()->prepare("SELECT * FROM groups");
    $stmtGroups->execute();
    $groups = $stmtGroups->fetchAll();
    $stmtCareers = Conexion::conectar()->prepare("SELECT * FROM carrers");
    $stmtCareers->execute();
    $careers = $stmtCareers->fetchAll();
    //se imprimen las opciones filtradas por el grupo obtenido mediante get
    echo '<div class="modal-content">
                <input name="student_id" type="hidden" value="' . $_GET["studentId"] . '">
                <h2>Edit Student</h2>
                <div class="row">
                    <div class="input-field col s12">
                        <input name="name" id="name" type="text" class="validate" value="' . $studentInfo['name'] . '" required>
                        <label for="name">Student Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select name="group" required>
                            <option value="" disabled selected>Student\'s group</option>';
    foreach ($groups as $row => $item) {
        if ($item["id"] == $studentInfo["id_group"]){
            echo '<option value="' . $item["id"] . '" selected>' . $item["name"] . '</option>';
        }else{
            echo '<option value="' . $item["id"] . '">' . $item["name"] . '</option>';
        }

    }
    echo '</select>
                        <label>Group</label>
                    </div>
                </div>
            </div>
            <div class="row">
                    <div class="input-field col s12">
                        <select name="carrer" required>
                            <option value="" disabled selected>Student\'s career</option>';
    foreach ($careers as $row => $item) {
        if ($item["id"] == $studentInfo["id_career"]){
            echo '<option value="' . $item["id"] . '" selected>' . $item["name"] . '</option>';
        }else{
            echo '<option value="' . $item["id"] . '">' . $item["name"] . '</option>';
        }

    }
    echo '</select>
                        <label>Career</label>
                    </div>
                </div>
               
            </div>
            <div class="modal-footer">
                <a href="index.php?action=alumnos"
                   class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
                <button name="editSSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
                </button>

            </div>';
}












