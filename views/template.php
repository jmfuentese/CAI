<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>
    <script type="text/javascript" src="views/sweetalert/sweetalert.js"></script>
    <link rel="stylesheet" type="text/css" href="views/sweetalert/sweetalert.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php
        if (isset($_GET["action"])){
            switch ($_GET["action"]){
                case "salidaSesion":
                    echo "Register leaving time";
                    break;
                case "alumnosSesion":
                    echo "Students attendance";
                    break;
                case "alumnosGrupo":
                    $groupI = Crud::getNameModel($_GET["idGrupo"], "groups");
                    echo "Students in group ".$groupI["name"];
                    break;
                case "alumnos":
                    echo "Students";
                    break;
                case "grupos":
                    echo "Groups";
                    break;
                case "sesiones":
                    echo "Sessions";
                    break;
                case "maestros":
                    echo "Teachers";
                    break;
                case "dashboard":
                    echo "Deleted sessions history";
                    break;
                case "verSesion":
                    echo "Session ".$_GET["idSesion"];
                    break;
                case "verSesionesAlumno":
                    echo $_GET["idAlumno"]." sessions";
            }
        }else{
            echo "New Session";
        }
        ?></title>
    <style>
        clock {
            color: black;
            text-align: center;
            font: 140px Lato, sans-serif;
            position: relative;
            top: 2vw;
        }
    </style>
</head>

<body onload="start()">


<div>
    <?php
    //Se llama la barra de navegación
    require_once "menus.php";
    $mvc = new Controller();
    $mvc->enlacesPaginasController();

    ?>
</div>

<!--Import jQuery-->
<script src="js/jquery-3.3.1.min.js"></script>
<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="js/materialize.min.js"></script>

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
<!-- DataTables -->
<script src="views/datatables/jquery.dataTables.js"></script>
<script src="views/datatables/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

<script type="text/javascript">
    //INICIALIZACIONES
    $(document).ready(function () {
        $('.materialboxed').materialbox();
    });
    $(document).ready(function () {
        $(".dropdown-trigger").dropdown();
    })
    //Inicializacion de modales
    $(document).ready(function () {
        $('.modal').modal();
    });
    //Inicializacion de TextFields
    $(document).ready(function () {
        M.updateTextFields();
    });
    //Inicializacion de los select
    $(document).ready(function () {
        $('select').formSelect();
    });
    //Inicializacion para el menu lateral (para dispositivos moviles)
    $(document).ready(function () {
        $('.sidenav').sidenav();
    });
    //Inicializacion de data tables
    $(document).ready(function () {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'print'
            ]
        });

    });

</script>

<!--SCRIPT DE RELOJ-->
<script type="text/javascript">
    function checkTime(m) {
        if (m < 10)
            m = "0" + m
        return m
    }

    function twelvehour(h) {
        if (h > 12) {
            var f = h - 12
            return f;
        }
        if (h == 0) {
            return 12
        }
        else
            return h
    }

    //Retornar AM o PM dependiendo de la hora
    function get_ampm(h) {
        if (h < 12)
            return "AM"
        if (h >= 12)
            return "PM"
    }

    //Inicializacion del reloj para el dashboards de usuarios registrados
    function start() {
        var today = new Date()
        var h = today.getHours()
        var m = today.getMinutes()
        //console.log("h: "+h)
        var ampm = get_ampm(h)

        //clean both sources
        m = checkTime(m)
        h = twelvehour(h)	//cambia a formato de 12 horas

        document.getElementById('time').innerHTML = h + ":" + m + " " + ampm
        //Configuracion del timer, ya que no se muestran segundos, el timer puede aumentarse aun mas
        var t = setTimeout(start, 2 * 1000) //cada 2 segundos
    }


</script>
<script>
    //Script para borrar maestros
    $(document).ready(function () {
        $(document).on('click', '#delete_teacher', function (e) {
            var teacherId = $(this).data('id');
            swalDeleteTeacher(teacherId);
            e.preventDefault();
        });
    });

    function swalDeleteTeacher(teacherId) {
        //console.log(teacherId);
        swal({
            type: 'warning',
            title: 'Are you sure?',
            text: 'The teacher will be removed from the users data base.',
            showCancelButton: true,
            confirmButtonClass: 'btn red darken-2',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: false
        }, function () {
            return new Promise(function (resolve) {
                $.ajax({
                    url: 'models/deleteTeacher.php',
                    type: 'POST',
                    data: 'teacherId=' + teacherId,
                    dataType: 'json'
                }).done(function (response) {
                    //swal('Deleted!', response.message, response.status);
                    swal({
                            type: response.status,
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        },
                        function () {
                            window.location.href = 'index.php?action=maestros';
                            tr.hide();
                        });
                }).fail(function () {
                    swal('Oops!', 'Something went wrong.', 'error');
                });
            });
        });
    }
</script>
<script>
    //Script para borrar alumnos
    $(document).ready(function () {
        $(document).on('click', '#delete_student', function (e) {
            var studentId = $(this).data('id');
            swalDeleteStudent(studentId);
            e.preventDefault();
        });
    });

    function swalDeleteStudent(studentId) {
        //console.log(teacherId);
        swal({
            type: 'warning',
            title: 'Are you sure?',
            text: 'Student will be removed from the data base.',
            showCancelButton: true,
            confirmButtonClass: 'btn red darken-2',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: false
        }, function () {
            return new Promise(function (resolve) {
                $.ajax({
                    url: 'models/deleteStudent.php',
                    type: 'POST',
                    data: 'studentId=' + studentId,
                    dataType: 'json'
                }).done(function (response) {
                    //swal('Deleted!', response.message, response.status);
                    swal({
                            type: response.status,
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        },
                        function () {
                            window.location.href = 'index.php?action=alumnos';
                            tr.hide();
                        });
                }).fail(function () {
                    swal('Oops!', 'Something went wrong.', 'error');
                });
            });
        });
    }
</script>
<script>
    //Script para borrar grupos
    $(document).ready(function () {
        $(document).on('click', '#delete_group', function (e) {
            var groupId = $(this).data('id');
            swalDeleteGroup(groupId);
            e.preventDefault();
        });
    });

    function swalDeleteGroup(groupId) {
        swal({
            type: 'warning',
            title: 'Are you sure?',
            text: 'Group will be removed from the data base.',
            showCancelButton: true,
            confirmButtonClass: 'btn red darken-2',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: false
        }, function () {
            return new Promise(function (resolve) {
                $.ajax({
                    url: 'models/deleteGroup.php',
                    type: 'POST',
                    data: 'groupId=' + groupId,
                    dataType: 'json'
                }).done(function (response) {
                    //swal('Deleted!', response.message, response.status);
                    swal({
                            type: response.status,
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        },
                        function () {
                            window.location.href = 'index.php?action=grupos';
                            tr.hide();
                        });
                }).fail(function () {
                    swal('Oops!', 'Something went wrong.', 'error');
                });
            });
        });
    }
</script>
<script>
    //Script para borrar unidades
    $(document).ready(function () {
        $(document).on('click', '#delete_unit', function (e) {
            var unitId = $(this).data('id');
            swalDeleteUnit(unitId);
            e.preventDefault();
        });
    });

    function swalDeleteUnit(unitId) {
        swal({
            type: 'warning',
            title: 'Are you sure?',
            text: 'Unit will be removed from the data base.',
            showCancelButton: true,
            confirmButtonClass: 'btn red darken-2',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: false
        }, function () {
            return new Promise(function (resolve) {
                $.ajax({
                    url: 'models/deleteUnit.php',
                    type: 'POST',
                    data: 'unitId=' + unitId,
                    dataType: 'json'
                }).done(function (response) {
                    //swal('Deleted!', response.message, response.status);
                    swal({
                            type: response.status,
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        },
                        function () {
                            window.location.href = 'index.php?action=unidades';
                            tr.hide();
                        });
                }).fail(function () {
                    swal('Oops!', 'Something went wrong.', 'error');
                });
            });
        });
    }
</script>
<script>
    //Editar Grupos
    $(document).ready(function () {
        $(document).on('click', '#edit_group', function (e) {
            var groupId = $(this).data('id');
            editGroupModal = $("#editGroupModalForm");
            $.get("models/fetchGroupInfo.php?groupId=" + groupId, function (data) {
                //Se reemplaza la estructura html del modal con la estructura obtenida
                //con la llamada asincrona al script fetchGroupInfo.php
                editGroupModal.html(data);
                console.log(data);
                //Inicializacion de modales
                //$('.modal').modal();
                //Inicializacion de TextFields
                M.updateTextFields();
                //Inicializacion de los select
                $('select').formSelect();

            });
            e.preventDefault();
        });
    });

    //Editar Alumnos
    $(document).ready(function () {
        $(document).on('click', '#edit_student', function (e) {
            var studentId = $(this).data('id');
            editStudentModalForm = $("#editStudentModalForm");
            $.get("models/fetchStudentInfo.php?studentId=" + studentId, function (data) {
                //Se reemplaza la estructura html del modal con la estructura obtenida
                //con la llamada asincrona al script fetchGroupInfo.php
                editStudentModalForm.html(data);
                console.log(data);
                //Inicializacion de modales
                //$('.modal').modal();
                //Inicializacion de TextFields
                M.updateTextFields();
                //Inicializacion de los select
                $('select').formSelect();

            });
            e.preventDefault();
        });
    });

    //Editar Maestros
    $(document).ready(function () {
        $(document).on('click', '#edit_teacher', function (e) {
            var teacherId = $(this).data('id');
            editTeacherModalForm = $("#editTeacherModalForm");
            $.get("models/fetchTeacherInfo.php?teacherId=" + teacherId, function (data) {
                //Se reemplaza la estructura html del modal con la estructura obtenida
                //con la llamada asincrona al script fetchGroupInfo.php
                editTeacherModalForm.html(data);
                console.log(data);
                //Inicializacion de modales
                //$('.modal').modal();
                //Inicializacion de TextFields
                M.updateTextFields();
                //Inicializacion de los select
                $('select').formSelect();

            });
            e.preventDefault();
        });
    });

    //Editar Unidades
    $(document).ready(function () {
        $(document).on('click', '#edit_unit', function (e) {
            var unitId = $(this).data('id');
            editUnitModalForm = $("#editUnitModalForm");
            $.get("models/fetchUnitInfo.php?unitId=" + unitId, function (data) {
                //Se reemplaza la estructura html del modal con la estructura obtenida
                //con la llamada asincrona al script fetchGroupInfo.php
                editUnitModalForm.html(data);
                console.log(data);
                //Inicializacion de modales
                //$('.modal').modal();
                //Inicializacion de TextFields
                M.updateTextFields();
                //Inicializacion de los select
                $('select').formSelect();

            });
            e.preventDefault();
        });
    });

</script>
</body>

</html>