<?php
if (!$_SESSION["validar"]) {
    //header("location:index.php?action=ingresar");
    echo "<script>window.location.href='index.php?action=login'</script>";
    exit();
}
?>
<div class="section">
    <main>
        <center>
            <h5 class="">Students's Sessions</h5>
            <div class="container">
                <div class='col s24'>
                    <table id="dataTable" class="table table-bordered striped responsive-table">
                        <thead>
                        <tr>
                            <th>Session</th>
                            <th>Registration</th>
                            <th>Name</th>
                            <th>Group</th>
                            <th>Career</th>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Start time</th>
                            <th>End time</th>
                            <th>Unit</th>
                            <th>Hours</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $s= new Controller();
                        $s-> showSessionsFromStudentController();?>
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
    </main>