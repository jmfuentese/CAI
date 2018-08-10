<?php
if (!$_SESSION["validar"]) {
    //header("location:index.php?action=ingresar");
    echo "<script>window.location.href='index.php?action=login'</script>";
    exit();
}
?>
<div class="section"></div>
<div class="container">
    <div class="row center-align">
        <h5 class="">Groups</h5>
    </div>

    <div class="row left">
        <?php
        if ($_SESSION["type"] == "superadmin") {
            echo "<a class='waves-effect waves-light btn modal-trigger left orange lighten-1' href='#addGroup'>Add Group<i class='material-icons left'>add</i></a>";
        }
        ?>
    </div>
    <br><br><br>
    <!-- Modal Agregar grupo -->
    <div id="addGroup" class="modal">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <h2>New Group</h2>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="level" id="level" type="text" class="validate" required>
                        <label for="level">Level</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select name="teacher">
                            <?php
                            $teachers = new Controller();
                            $teachers->getTeachersController();
                            ?>
                        </select>
                        <label>Teacher</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>-->
                <a href="index.php?action=grupos"
                   class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
                <button name="newGSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
                </button>

            </div>
        </form>
        <?php $i = new Controller();
        if(isset($_POST['newGSubmit'])){
            $i->insertGroupController();
        }
        ?>
    </div>
    <!-- Modal Editar Grupo -->
    <div id="editGroup" class="modal">
        <form id="editGroupModalForm" method="post" enctype="multipart/form-data"></form>
        <?php
        if (isset($_POST["editGSubmit"]) && isset($_POST["group_id"])){
            $i = new Controller();
            $i->updateGroupController($_POST["group_id"]);
        }
        ?>
    </div>

    <div class='col s24'>
        <table id="dataTable" class="table table-bordered striped responsive-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Level</th>
                <?php if ($_SESSION["type"] == "supervisor" || $_SESSION["type"] == "superadmin") {
                    echo "<th>Teacher</th>";
                    echo "<th>Actions</th>";
                } ?>

            </tr>
            </thead>
            <tbody>
            <?php
            $g = new Controller();
            $g->showGroupsController();
            ?>
            </tbody>
        </table>
    </div>
</div>
