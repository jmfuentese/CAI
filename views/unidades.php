<?php
if (!$_SESSION["validar"]) {
    //header("location:index.php?action=ingresar");
    echo "<script>window.location.href='index.php?action=index'</script>";
    exit();
}
$a = new Controller();
?>
<div class="section"></div>
<div class="container">
    <div class="row center-align">
        <h5 class="">Units</h5>
    </div>
    <div class="row left">
        <?php
        if ($_SESSION["type"] == "superadmin") {
            echo "<a class='waves-effect waves-light btn modal-trigger left orange lighten-1' href='#addUnit'>Add Unit<i class='material-icons left'>add</i></a>";
        }
        ?>
    </div>
    <br><br><br>
    <!-- Modal Agregar Unidades -->
    <div id="addUnit" class="modal">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <h2>New Unit </h2>
                <div class="row">
                    <div class="input-field col s12">
                        <input name="name" id="name" type="text" class="validate">
                        <label for="name">Unit name</label>
                    </div>
                    <div class="input-field col s6">
                        <label>Start Date</label>
                        <br/><br/>
                        <input name="startDate" id="startDate" type="date" class="validate">
                    </div>
                    <div class="input-field col s6">
                        <label>End date</label>
                        <br/><br/>
                        <input name="endDate" id="endDate" type="date" class="validate">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?action=unidades"
                   class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
                <button name="newSSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
                </button>

            </div>
        </form>
        <?php
        if (isset($_POST["newSSubmit"])){
            $a->insertUnitController();
        }
        ?>
    </div>
    <!-- Modal Editar Unidad -->
    <div id="editUnit" class="modal">
        <form id="editUnitModalForm" method="post" enctype="multipart/form-data"></form>
        <?php
        if (isset($_POST["editUSubmit"]) && isset($_POST["unit_id"])){
            $a->updateUnitController($_POST["unit_id"]);
        }
        ?>
    </div>
    <div class='col s24'>
        <table id="dataTable" class="table table-bordered striped responsive-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Start date</th>
                <th>End date</th>
            </tr>
            </thead>

            <tbody>
            <?php $a = new Controller();
            $a->showUnitsController(); ?>
            </tbody>
        </table>
    </div>
</div>