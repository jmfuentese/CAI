<?php

class Paginas{ //Clase paginas incluye la funcion que se encarga de enlazar las paginas entre ellas

	public static function enlacesPaginasModel($link){
    //Se determinan todos los posibles valores que puede tener un action (que es el parametro que recibe en $link)
		if($link == "alumnos" || $link == "grupos"  || $link == "maestros"
			|| $link == "registrarSesion" || $link == "dashboard" || $link == "salir" || $link == "login"
            || $link == "alumnosGrupo" || $link == "registrarSesion" || $link == "salidaSesion"
            || $link == "alumnosSesion" || $link == "verSesion" || $link == "unidades" || $link == "verSesionesAlumno"){

			$module =  "views/".$link.".php";

		} else if ($link =="index" || $link == "") { //Si no es ninguno de estos actions, y es el index o esta vacio
		    //redirige a la pagina de inicio que es registrarSesion
			$module = "views/registrarSesion.php";
		}else if($link == "sesiones"){
		    $module = "views/maestroAlumnos.php";
        }else{
            //Si la pagina no existe o no se encuentra, se mostará un error 404
			$module =  "views/404.html";

		}
		return $module;

	}

}

?>