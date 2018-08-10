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
            <h5 class="">Teacher-Student Relation</h5>
            <div class="container">
                <div class='col s24'>
                    <table id="dataTable" class="table table-bordered striped responsive-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Details</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $s= new Controller();
                        $s-> showTeachersForSessionsController();?>
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
    </main>