<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>
<nav>
    <div class="nav-wrapper purple darken-3">
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <a href="index.php" class="brand-logo">CAI Control</a>
        <!--BARRA DE NAVEGACION PARA PANTALLAS GRANDES-->
        <ul class="right hide-on-med-and-down">
            <?php if ($_SESSION["validar"]==false){
                echo " <li><a href='index.php'>Start Session<i class='material-icons right'>alarm_on</i></a></li>";
                echo " <li><a href='index.php?action=salidaSesion'>End Session<i class='material-icons right'>alarm_on</i></a></li>";
                echo "<li><a href='index.php?action=login'>Login<i class='material-icons right'>input</i></a></li>";
            } elseif ($_SESSION["validar"]==true){
                echo "<li><a href='index.php?action=dashboard'>Dashboard<i class='material-icons right'>dashboard</i></a></li>";
                switch ($_SESSION["type"]) {
                    case "superadmin":
                        echo "<li><a href='index.php?action=grupos'>Groups<i class='material-icons right'>group</i></a></li>
                     <li><a href='index.php?action=sesiones'>Sessions<i class='material-icons right'>timelapse</i></a></li>
                    <li><a href='index.php?action=maestros'>Teachers<i class='material-icons right'>person</i></a></li>
                    <li><a href='index.php?action=alumnos'>Students<i class='material-icons right'>school</i></a></li>
                    <li><a href='index.php?action=unidades'>Units<i class='material-icons right'>assignment</i></a></li>";
                        break;
                    case "supervisor":

                        echo "<li><a href='index.php?action=grupos'>Groups<i class='material-icons right'>group</i></a></li>
                              <li><a href='index.php?action=sesiones'>Sessions<i class='material-icons right'>timelapse</i></a></li>
                              <li><a href='index.php?action=alumnos'>Students<i class='material-icons right'>school</i></a></li>";
                        break;
                    case "teacher":

                        echo "<li><a href='index.php?action=grupos'>My Groups<i class='material-icons right'>group</i></a></li>";
                        echo "<li><a href='index.php?action=alumnosSesion'>My Student's Sessions<i class='material-icons right'>timelapse</i></a></li>";
                }
                echo " <li><a href='index.php?action=salir'>Log out<i class='material-icons right'>exit_to_app</i></a></li>
                        <li><i class='material-icons right'>p</i></li>";
                echo "<li>".$_SESSION["name"]."<i class='material-icons right'>account_circle</i></li>";
            } ?>
        </ul>
        <!--MENU LATERAL PARA DISPOSITIVOS MOVILES-->
        <ul class="sidenav" id="mobile-demo">
            <?php switch ($_SESSION["type"]) {
                case "superadmin":
                    echo "<li><a href='index.php?action=dashboard'>Dashboard<i class='material-icons right'>dashboard</i></a></li>";
                    echo "<li><a href='index.php?action=grupos'>Groups<i class='material-icons right'>group</i></a></li>
                        <li><a href='index.php?action=sesiones'>Sessions<i class='material-icons right'>timelapse</i></a></li>
                        <li><a href='index.php?action=maestros'>Teachers<i class='material-icons right'>person</i></a></li>";
                    break;
                case "supervisor":
                    echo "<li><a href='index.php?action=dashboard'>Dashboard<i class='material-icons right'>dashboard</i></a></li>";
                    echo "<li><a href='index.php?action=sesiones'>Sesiones<i class='material-icons right'>timelapse</i></a></li>";
                    break;
                case "teacher":
                    echo "<li><a href='index.php?action=dashboard'>Dashboard<i class='material-icons right'>dashboard</i></a></li>";
            } ?>

            <li><a href="index.php?action=alumnos">Alumnos<i class="material-icons right">school</i></a></li>
            <li><a href="index.php?action=salir">Salir<i class='material-icons right'>exit_to_app</i></a></li>
            <li><?php
                    echo $_SESSION["name"];
                ?>
            <i class='material-icons right'>user</i></li>
        </ul>
    </div>
</nav>


<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
</html>
