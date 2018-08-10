<?php

if (isset($_GET["groupId"])) {
    //se incluye el archivo de conexion
    require_once("conexion.php");
    require_once("../controllers/controller.php");
    //se prepara el query para hacer la consulta a la base de datos
    $statement = Conexion::conectar()->prepare("SELECT * FROM groups WHERE id = :id");
    $statement->bindParam(":id", $_GET['groupId'], PDO::PARAM_INT);
    //se ejecuta la query
    $statement->execute();
    $groupInfo = $statement->fetch();
    $stmtTeachers = Conexion::conectar()->prepare("SELECT * FROM users WHERE teacher = 1");
    $stmtTeachers->execute();
    $teachers = $stmtTeachers->fetchAll();
    //se imprimen las opciones filtradas por el grupo obtenido mediante get
    echo '<div class="modal-content">
                <input name="group_id" type="hidden" value="'.$groupInfo["id"].'">
                <h2>Edit Group</h2>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="level" id="level" type="text" class="validate" value="'.$groupInfo['name'].'" required>
                        <label for="level">Level</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select name="teacher">';
                            foreach ($teachers as $row => $item) {
                                if ($item["id"] == $groupInfo["id_teacher"]){
                                    echo '<option value="' . $item["id"] . '" selected>' . $item["name"] . '</option>';
                                }else{
                                    echo '<option value="' . $item["id"] . '">' . $item["name"] . '</option>';
                                }

                            }
    echo                '</select>
                        <label>Teacher</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?action=grupos"
                   class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
                <button name="editGSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
                </button>
            </div>';

}












