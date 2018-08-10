<?php
if (!$_SESSION["validar"]) {
    //header("location:index.php?action=ingresar");
    echo "<script>window.location.href='index.php?action=login'</script>";
    exit();
}
if ($_SESSION["type"] != "superadmin") {
    exit();
}
$m = new Controller();
?>
<div class="section"></div>
<div class="container">
    <div class="row center-align">
        <h5 class="">Teachers</h5>
    </div>
    <php class="row left">
        <?php
        if ($_SESSION["type"] == "superadmin") {
            echo "<a class='waves-effect waves-light btn modal-trigger left orange lighten-1' href='#addTeacher'>Add Teacher<i class='material-icons left'>add</i></a>";
        }
        ?>
    </php>
    <br><br><br>
    <!-- Modal Agregar Maestro -->
    <div id="addTeacher" class="modal">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <h2>New Teacher</h2>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="name" id="name" type="text" class="validate" required>
                        <label for="first_name">Teacher Name</label>
                    </div>
                    <div class="input-field col s6">
                        <input name="last_name" id="last_name" type="text" class="validate" required>
                        <label for="last_name">Teacher Last Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="user" id="user" type="text" class="validate" required>
                        <label for="user">Login user</label>
                    </div>
                    <div class="input-field col s6">
                        <input name="password" id="password" type="password" class="validate" required>
                        <label for="password">Login password</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?action=maestros"
                   class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
                <button name="newTSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
                </button>
            </div>
        </form>
        <?php
        if (isset($_POST["newTSubmit"])){
            $m->insertTeacherController();
        }
        ?>
    </div>
    <!-- Modal Editar Maestro -->
    <div id="editTeacher" class="modal">
        <form id="editTeacherModalForm" method="post" enctype="multipart/form-data"></form>
        <?php
        if (isset($_POST["editTSubmit"]) && isset($_POST["teacher_id"])){
            $m->updateTeacherController($_POST["teacher_id"]);
        }
        ?>
    </div>
    <div class='col s24'>
        <table id="dataTable" class="table table-bordered striped responsive-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <?php if ($_SESSION["type"] == "superadmin") {
                    echo "<th>Actions</th>";
                } ?>
            </tr>
            </thead>

            <tbody>
            <?php $t = new Controller();
            $t->showTeachersController(); ?>
            </tbody>
        </table>
    </div>
</div>