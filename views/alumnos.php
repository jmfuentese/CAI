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
        <h5 class="">Students</h5>
    </div>
    <div class="row left">
        <?php
        if ($_SESSION["type"] == "superadmin") {
            echo "<a class='waves-effect waves-light btn modal-trigger left orange lighten-1' href='#addStudent'>Add Student<i class='material-icons left'>add</i></a>";
        }
        ?>
    </div>
    <br><br><br>
    <!-- Modal Agregar Alumno -->
    <div id="addStudent" class="modal">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <h2>New Student </h2>
                <div class="row">
                    <div class="input-field col s12">
                        <input name="registration" id="registration" type="number" class="validate">
                        <label for="name">Student Registration</label>
                    </div>
                    <div class="input-field col s6">
                        <input name="name" id="name" type="text" class="validate">
                        <label for="name">Student Name</label>
                    </div>
                    <div class="input-field col s6">
                        <input name="last_name" id="last_name" type="text" class="validate">
                        <label for="last_name">Student Last Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select name="group">
                            <option value="" disabled selected>Choose the student's group</option>
                            <?php
                            $a = new Controller();
                            $a->getGroupsController(); ?>
                        </select>
                        <label>Group</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select name="carrer">
                            <option value="" disabled selected>Choose the student's career</option>
                            <?php
                            $a = new Controller();
                            $a->getCarrersController(); ?>
                        </select>
                        <label>Career</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <label for ="imagen">Student's Photo</label>
                        <br><br>
                        <input name="imagen" size="30" type="file">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?action=alumnos"
                   class="modal-close waves-effect waves-light btn red lighten-1 text-white">Cancel</a>
                <button name="newSSubmit" class="modal-close waves-effect waves-light btn green darken-1" type="submit">Save
                </button>

            </div>
        </form>
        <?php
        if (isset($_POST["newSSubmit"])){
            $a->insertStudentController();
        }
        ?>
    </div>
    <!-- Modal Editar Alumno -->
    <div id="editStudent" class="modal">
        <form id="editStudentModalForm" method="post" enctype="multipart/form-data"></form>
        <?php
        if (isset($_POST["editSSubmit"]) && isset($_POST["student_id"])){
            $a->updateStudentController($_POST["student_id"]);
        }
        ?>
    </div>
    <div class='col s24'>
        <table id="dataTable" class="table table-bordered striped responsive-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Group</th>
                <th>Carrer</th>
                <?php if ($_SESSION["type"] == "superadmin" || $_SESSION["type"] == "supervisor") {
                    echo "<th>Teacher</th>";
                } ?>
                <th>Photo</th>
                <?php if ($_SESSION["type"] == "superadmin") {
                    echo "<th>Actions</th>";
                } ?>

            </tr>
            </thead>

            <tbody>
            <?php $a = new Controller();
            $a->showStudentsController(); ?>
            </tbody>
        </table>
    </div>
</div>