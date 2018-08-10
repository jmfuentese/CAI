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
            <h5 class="">Students in this group</h5>
            <div class="container">
                <div class='col s24'>
                    <table id="dataTable" class="table table-bordered striped responsive-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Group</th>
                            <th>Carrer</th>
                            <?php if ($_SESSION["type"]=="superadmin" ||$_SESSION["supervisor"]){
                                echo "<th>Teacher</th>";
                            }?>
                            <th>Photo</th>

                            <?php if ($_SESSION["type"]=="superadmin"){
                                echo "<th>Actions</th>";
                            }?>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $a= new Controller();
                        $a-> showStudentsByGroupController();?>
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
    </main>