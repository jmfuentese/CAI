<?php

if (isset($_GET["unitId"])) {
    //se incluye el archivo de conexion
    require_once("conexion.php");
    require_once("../controllers/controller.php");
    //se prepara el query para hacer la consulta a la base de datos
    $statement = Conexion::conectar()->prepare("SELECT * FROM units WHERE id = :id");
    $statement->bindParam(":id", $_GET['unitId'], PDO::PARAM_INT);
    //se ejecuta la query
    $statement->execute();
    $unitInfo = $statement->fetch();


    echo '<div class="modal-content">
                <input name="unit_id" type="hidden" value="' . $_GET["unitId"] . '">
                <h2>Edit Unit</h2>
                <div class="row">
                    <div class="input-field col s12">
                        <input name="name" id="name" type="text" class="validate" value="' . $unitInfo['name'] . '" required>
                        <label for="name">Unit Name</label>
                    </div>
                </div>
                <div class="input-field col s6">
                    <label>Start Date</label>
                    <br/><br/>
                    <input name="startDate" id="startDate" type="date" class="validate" value="'.$unitInfo["start_date"].'">
                </div>
                <div class="input-field col s6">
                    <label>End Date</label>
                    <br/><br/>
                    <input name="endDate" id="endDate" type="date" class="validate" value="'.$unitInfo["end_date"].'">
                </div>';

    echo '<div class="modal-footer">
            <a href="index.php?action=unidades"
               class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
            <button name="editUSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
            </button>

        </div>';
}












