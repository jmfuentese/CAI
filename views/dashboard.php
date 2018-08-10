<?php
if (!$_SESSION["validar"]) {
    echo "<script>window.location.href='index.php?action=index'</script>";
    exit();
}
//if ($_SESSION["type"] != "superadmin") {
//    exit();
//}
$d = new Controller();
?>
<div class="container center center-align">
    <clock >
        <div id="time"></div>
    </clock>
    <div class="row center-align" style="margin: 50px 20px 10px 0;">
        <div style="width:30%; margin:0 auto;">
            <div class="card-panel teal white-text">
            <span class="card-title ">
                <b style="font-size: x-large">Students in last session</b>
            </span>
                <p><?=$d->totalStudentsInSession();?></p>
            </div>
        </div>
    </div>
    <h3>History of deleted sessions</h3>
    <p>Sessions with no end time registered will be deleted after 4 hours from the data base and will appear in the history below.</p>
    <div class="row">
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Session ID</th>
                <th>Student ID</th>
                <th>Teacher</th>
                <th>Group</th>
                <th>Date</th>
            </tr>
            </thead>

            <tbody>
            <?php $a = new Controller();
            $a->getDeletedSessions(); ?>
            </tbody>
        </table>
    </div>

</div>