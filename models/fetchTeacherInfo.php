<?php

if (isset($_GET["teacherId"])) {
    //se incluye el archivo de conexion
    require_once("conexion.php");
    require_once("../controllers/controller.php");
    //se prepara el query para hacer la consulta a la base de datos
    $statement = Conexion::conectar()->prepare("SELECT * FROM users WHERE id = :id");
    $statement->bindParam(":id", $_GET['teacherId'], PDO::PARAM_INT);
    //se ejecuta la query
    $statement->execute();
    $teacherInfo = $statement->fetch();
    //se imprimen las opciones filtradas por el grupo obtenido mediante get
    echo '<div class="modal-content">
                <input name="teacher_id" type="hidden" value="' . $_GET["teacherId"] . '">
                <h2>Edit Teacher</h2>
                <div class="row">
                    <div class="input-field col s12">
                        <input name="name" id="name" type="text" class="validate" value="' . $teacherInfo['name'] . '" required>
                        <label for="first_name">Teacher Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="user" id="user" type="text" class="validate" value="' . $teacherInfo['user'] . '" required>
                        <label for="user">Login user</label>
                    </div>
                    <div class="input-field col s6">
                        <input name="password" id="password" type="password" class="validate" value="' . $teacherInfo['password'] . '" required>
                        <label for="password">Login password</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?action=maestros"
                   class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
                <button name="editTSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
                </button>
            </div>';

}












