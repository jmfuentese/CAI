<?php
if (!$_SESSION["validar"]) {
    //header("location:index.php?action=ingresar");
    echo "<script>window.location.href='index.php?action=login'</script>";
    exit();
}
$s = new Controller();
?>
<div class="section"></div>
<div class="container">
    <div class="row center-align">
        <h5 class="">Sessions</h5>
    </div>

    <br><br><br>
        <div class='col s24'>
            <div class="row left">
                <?php
                if ($_SESSION["type"] == "superadmin") {
                    //echo "<a class='waves-effect waves-light btn modal-trigger left' href='#addSession'>Add Session<i class='material-icons left'>add</i></a>";
                    echo "<a class='waves-effect waves-light btn modal-trigger left orange lighten-1' href='index.php?action=registrarSesion'>Add Session<i class='material-icons left'>add</i></a>";
                }
                ?>
            </div>
            <table id="dataTable" class="table table-bordered striped responsive-table">
                <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Students in session</th>
                    <th>Date</th>
                </tr>
                </thead>

                <tbody>
                <?php $s->sessionsListController(); ?>
                </tbody>
            </table>
        </div>

</div>