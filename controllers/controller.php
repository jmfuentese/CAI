<?php

#Esta es la clase que contiene todos los métodos que se encargan de llamar al modelo y hacer la conexión con la vista.
class Controller
{

    #Se llama a la plantilla 
    #-----------------------

    public function pagina()
    {

        include "views/template.php";

    }

    #ENLACES
    #Esta funcion se encarga de obtener el valor del parametro action enviado a traves de la url
    #para determinar la ruta a la que se accedera en el sistema
    #-------------------------------
    public function enlacesPaginasController()
    {
        #se valida si el parametro action esta declarado
        if (isset($_GET['action'])) {
            #se toma el valor del parametro y se almacena en una variable
            $enlaces = $_GET['action'];

        } else {
            $enlaces = "index";
        }

        $resp = Paginas::enlacesPaginasModel($enlaces);
        //Se incluye la pagina que se desea ver
        include $resp;
    }

    #Login
    #Validacion para el acceso al sistema
    #En esta funcion se verifican las credenciales de acceso del usuario
    #Se valida tambien el timpo de usuario (maestro, supervisor, superadmin)
    public static function loginController()
    {
        if (isset($_POST["btn_login"])) {
            if (isset($_POST["user"]) && isset($_POST["password"])) {
                $datos = array("user" => $_POST["user"], "password" => $_POST["password"]);
                $respuesta = Crud::loginModel("users", $datos);

                if (($respuesta["user"] == $_POST["user"]) && ($respuesta["password"] == $_POST["password"])) {
                    $_SESSION["validar"] = true; //Se valida la entrada al sistema y se permite ver el menú, entre otras cosas.
                    $_SESSION["id"] = $respuesta["id"]; //Se almacena el id del usuario que ingresó para utilizarlo
                    //más adelante si así se requiere.
                    $usr= Crud::getNameModel($_SESSION["id"], "users");
                    $_SESSION["name"]= $usr["name"];
                    if ($respuesta['superadmin'] == 1) { //Si el usuario que ingresó es el superadmin
                        $_SESSION["type"] = "superadmin"; //Se almacena el tipo de la sesión para utilizarlo más adelante.
                        //Se despliega un mensaje de bienvenida para el superadmin.
                        echo "<script>
                                swal({
                                  type:'success',
                                  title: 'Correct credentials!',
                                  text: 'Welcome',
                                  showConfirmButton: false,
                                  timer:1500
                                },
                                function () {
                                    window.location.href = 'index.php?action=dashboard';
                                    tr.hide();
                                 });
                          </script>";


                    } elseif ($respuesta["supervisor"] == 1) { //Si el usuario que ingresó es un supervisor
                        $_SESSION["type"] = "supervisor"; //Se almacena el tipo de usuario
                        //Se despliega un mensaje de bienvenida para el supervisor.
                        echo "<script>
                                swal({ 
                                  type:'success',
                                  title: 'Correct credentials!',
                                  text: 'Welcome Supervisor',
                                  showConfirmButton: false,
                                  timer:1500
                                },
                                function () {
                                    window.location.href = 'index.php?action=dashboard';
                                    tr.hide();
                                 });
                          </script>";
                    } elseif ($respuesta["teacher"] == 1) { //Se compara si la el tipo de usuario es un maestro
                        $_SESSION["type"] = "teacher"; //Se almacena el tipo de usuario que ingresó
                        //Se despliega el mensaje de bienvenida para el maestro.
                        echo "<script>
                                swal({
                                  type:'success',
                                  title: 'Correct credentials!',
                                  text: 'Welcome teacher',
                                  showConfirmButton: false,
                                  timer:1500
                                },
                                function () {
                                    window.location.href = 'index.php?action=dashboard';
                                    tr.hide();
                                 });
                          </script>";
                    }
                } else { //Se las credenciales ingresadas no coincidieron con algunas de la base de datos
                    //el sistema despliega un mensaje que alerta al usuario de que las credenciales son incorrectas.
                    echo "<script>
                                swal({
                                  type:'error',
                                  title: 'Invalid credentials!',
                                  text: 'Try again',
                                  showConfirmButton: false,
                                  timer:1500
                                },
                                function () {
                                    window.location.href = 'index.php?action=index'; 
                                    tr.hide();
                                 });
                          </script>";
                    //El sistema devuelve al index para que el usuario vuelva a ingresar las credenciales.
                }
            }
        }
    }

    //La funcion showGroupsController se encarga de mostrar todos los grupos que existen en la base de datos.
    public static function showGroupsController()
    {
        //Si la sesión ingresada es un superadmin, se muestran todos los grupos, los maestros que estan a cargo y se
        //puede editar y eliminar.
        if ($_SESSION["type"] == "superadmin") {
            $respuesta = Crud::showModel("groups");
            #El constructor foreach proporciona un modo sencillo de iterar sobre arrays. foreach funciona sólo sobre arrays
            # y objetos, y emitirá un error al intentar usarlo con una variable de un tipo diferente de datos o una variable no inicializada.
            foreach ($respuesta as $row => $item) {
                $name = Crud::getNameModel($item["id_teacher"], "users"); //Se utiliza el modelo para obtener el nombre del maestro de cada id_teacher
                //que tiene cada uno de los gupos que devuelva el modelo a través de la función ShowModel.

                //Se muestra el id del grupo, el nombre (nivel) y el maestro que lo educa. También se proporcionan los botones de modificar
                //y eliminar, y el boton de "ver grupo" para poder ver los alumnos que contiene ese grupo.
                echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                        <td>' . $name["name"] . '</td>
                        <td>
                            <a id="edit_group" data-id="' . $item['id'] . '" class="modal-trigger " href="#editGroup"><button class="btn btn-info teal darken-1">Edit<i class="material-icons prefix blue-text left">edit</i></button></a>
                            <a id="delete_group" data-id="' . $item['id'] . '" href="javascript:void(0)"><button class="btn btn-danger teal darken-1">Delete<i class="material-icons prefix red-text left">delete</i></button></a>
                            <a href="index.php?action=alumnosGrupo&idGrupo=' . $item["id"] . '"><button class="btn btn-info teal blue darken-1">See Group <i class="material-icons prefix white-text left">visibility</i></button></a>
                        </td>
                    </tr>';
            }
            if (isset($_GET["idGrupo"])) { //Si se ha presionado el botón de "ver grupo" el controller llama la funcion del modelo que filtra
                //los alumnos a través del id del grupo que se eligió para ver más.
                $idGrupo = $_GET["idGrupo"]; //Se obtiene el id del grupo del cual se presionó el botón de "see group"  y se almacena en una variable
                self::showStudentsByGroupController($idGrupo);//La variable obtenida se utiliza en la funcion de mostrar los estudiante segun el grupo
                //al que pertenecen.
            }

        } elseif ($_SESSION["type"] == "teacher") { //Si el tipo de sesión ingresada es un teacher, se mostrarán solo los grupos a los que
            //este maestro imparte clases. De lo contrario no podra ver los demás grupos.
            //También se muestra el botón de ver grupo, con el que podrá ver los alumnos que pertenecen a ese grupo.
            $respuesta = Crud::showGroupTeacherModel(); //Se llama el modelo que trae a los grupos según el usuario que ingresó.
            //En este funcion se utiliza el id de la sesion para traer los grupos segun el maestro.
            foreach ($respuesta as $row => $item) {
                echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                        <td><a href="index.php?action=alumnosGrupo&idGrupo=' . $item["id"] . '">
                        <button class="btn btn-info blue lighten-1">See Group<i class="material-icons prefix white-text left">visibility</i></button>
                        </a></td>
                     </tr>';
            }
            if (isset($_GET["idGrupo"])) { //Si se ha presionado el boton de "see group" se ha mandado el idGrupo por metodo GET,
                //entonces se obtiene y se almacena en una variable.
                $idGrupo = $_GET["idGrupo"];
                self::showStudentsByGroupController($idGrupo); //Se utiliza el modelo que muestra los alumnos segun el id del grupo
            }
        } elseif ($_SESSION["type"] == "supervisor") { //Si el usuario es un supervisor, solo puede ver los grupos pero no puede editar
            //ni eliminar
            $respuesta = Crud::showModel("groups");
            foreach ($respuesta as $row => $item) {
                $name = Crud::getNameModel($item["id_teacher"], "users");
                echo '<tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $item["name"] . '</td>
                    <td>' . $name["name"] . '</td>
                    <td><a href="index.php?action=alumnosGrupo&idGrupo=' . $item["id"] . '">
                        <button class="btn btn-info blue lighten-1">See Group<i class="material-icons prefix white-text left">visibility</i></button>
                        </a></td>
                 </tr>';
            }

            if (isset($_GET["idGrupo"])) { //Si se ha presionado el boton de "see group" se ha mandado el idGrupo por metodo GET,
                //entonces se obtiene y se almacena en una variable.
                $idGrupo = $_GET["idGrupo"];
                self::showStudentsByGroupController($idGrupo); //Se utiliza el modelo que muestra los alumnos segun el id del grupo
            }
        }
    }

    //Funcion que muestra todos los maestros de la base de datos en un select.
    public static function getTeachersController()
    {
        //Parámetros: tabla donde vamos a buscar
        // y columna que queremos verificar
        $respuesta = Crud::showEmployeeModel("users", "teacher"); //Si en la tabla usuario, el usuario es un teacher (la columna teacher,
        //que se pasa como parámetro, está en 1, o sea que ese usuario es teacher) se coloca en el select.

        //Por cada maestro que aparezca en la base de datos se genera una opción del select en la cual se muestra el nombre
        //pero cabe recalcar que lo que se utiliza como valor importante es el id del empleado.
        foreach ($respuesta as $row => $item) {
            echo '<option value="' . $item["id"] . '">' . $item["name"] . '</option>';
        }
    }

    //Funcion que agrega nuevos grupos a la base de datos.
    public static function insertGroupController()
    {
        if (isset($_POST["level"]) && isset($_POST["teacher"])) { //Si el usuario llenó los datos para agregar un nuevo grupo
            $datos = array("level" => $_POST["level"], "id_teacher" => $_POST["teacher"]); //Se guardan los datos en un arreglo
            $respuesta = Crud::insertGroupModel($datos); //Estos datos se pasan al modelo que se encargará de insertarlos en la base de
            //datos, los toma como parámetro y los inserta.
            if ($respuesta) { //Si se realizó la inserción se muestra el mensaje de que se ha agregado correctamente el grupo
                echo "<script>
                    swal({
                      type:'success',
                      title: 'Group saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=grupos';
                        tr.hide();
                     });
              </script>";
            } else {//Si la inserción fallo, el grupo no se inserto en la bd y se le informa al usuario.
                echo "<script>
                    swal({
                      type:'error',
                      title: 'No changes saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=grupos';
                        tr.hide();
                     });
                </script>";
            }

        }
    }

    //Funcion que se encarga de actualizar los grupos
    public static function updateGroupController($idG)
    {
        if (isset($_POST["level"])) { //Si el usuario ingresó los datos en el formulario
            $datos = array("level" => $_POST["level"], "id_teacher" => $_POST["teacher"]); //Se guardan en un arreglo los datos que ingresó
            //y que se obtuvieron por métdo POST previamente.
            $respuesta = new Crud(); //Se hace una instancia de la clase del modelo
            $r = $respuesta->updateGroupModel("groups", $datos, $idG); //Y se manda llamar el modelo que actualiza los grupos.
            if ($r) { //Si la actualización se realizó entonces imprime un mensaje de confirmación, de lo contrario se enviará un
                //mensaje de que los datos no fueron actualizados
                echo "<script>
                            swal({
                              type:'success',
                              title: 'Group updated successfuly!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=grupos';
                                tr.hide();
                             }
                            );
                          </script>";
            } else {
                //Se imprime el mensaje de que no se puede actualizar el grupo, ya que ocurrió un error.
                echo "<script>
                            swal({
                              type:'error',
                              title: 'Could not update group!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=grupos';
                                tr.hide();
                             });
                        </script>";
            }

        }
    }

    //Funcion que muestra todos los maestros que existen en la base de datos
    public static function showTeachersController()
    {
        //Se emplea un filtro de todos los maestros que se van a mostrar, a través de cada uno de los tipos de usuario.
        //Solo el superadmin y el supervisor pueden ver los maestros que existen. Solo el superadmin puede editar y eliminar.
        if ($_SESSION["type"] == "superadmin") {
            $respuesta = Crud::showEmployeeModel("users", "teacher");
            #El constructor foreach proporciona un modo sencillo de iterar sobre arrays. foreach funciona sólo sobre arrays
            # y objetos, y emitirá un error al intentar usarlo con una variable de un tipo diferente de datos o una variable no inicializada.
            foreach ($respuesta as $row => $item) {
                echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                        <td>
                            <a id="edit_teacher" data-id="' . $item['id'] . '" class="modal-trigger " href="#editTeacher"><button class="btn btn-info teal darken-1">Edit<i class="material-icons prefix blue-text left">edit</i></button></a>
                            ';
                    if ($item["superadmin"] != 1){
                        echo '<a id="delete_teacher" data-id="' . $item['id'] . '" href="javascript:void(0)"><button class="btn btn-danger teal darken-1">Delete<i class="material-icons prefix red-text left">delete</i></button></a>';
                    }

                    echo '
                        </td>
                    </tr>';
            }

        } elseif ($_SESSION["type"] == "supervisor") {
            $respuesta = Crud::showEmployeeModel("users", "teacher");
            #El constructor foreach proporciona un modo sencillo de iterar sobre arrays. foreach funciona sólo sobre arrays
            # y objetos, y emitirá un error al intentar usarlo con una variable de un tipo diferente de datos o una variable no inicializada.

            //Como el tipo de usuario solo permite ver los maestros, se mostraran su id de empleado y el nombre.
            foreach ($respuesta as $row => $item) {
                echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                     </tr>';
            }
        }
    }

    //Funcion que muestra los grupos en un select.
    public static function getGroupsController()
    {
        //Se llama al modelo con la tabla que queremos que nos muestre
        $respuesta = Crud::showModel("groups");
        foreach ($respuesta as $row => $item) { //y se despliega cada registro que se obtuvo como repuesta, del cual se
            //mostrara el nombre del grupo y como valor se obtendrá el id, segun la selección que haga el usuario.
            echo '<option value="' . $item["id"] . '" >' . $item["name"] . '</option>';
        }
    }

    //Similar a la funcion anterior, muestra las carreras que estan contenidas en la base de datos.
    public static function getCarrersController()
    {
        //Se llama al modelos para que a través de la funcion ShowModel, muestra todo lo de la tabla que se está pasando
        //como parámetro.
        $respuesta = Crud::showModel("carrers"); //obtiene todas las carreras de la base de datos.
        foreach ($respuesta as $row => $item) { //mostrará el nombre de la carrera y como valor se tomará el id de dicha carrera
            echo '<option value="' . $item["id"] . '" >' . $item["name"] . '</option>';
        }
    }

    //Funcion que muestra los alumnos que se encuenentran en la base de datos, con esta funcion se hace el llenado de las tablas que se muestran
    //en cada uno de los menus de cada usuario.
    public static function showStudentsController()
    {
        if ($_SESSION["type"] == "superadmin") { //Si la vista es administrador, el usuario puede editar y eliminar.
            $respuesta = Crud::showModel("students");
            #El constructor foreach proporciona un modo sencillo de iterar sobre arrays. foreach funciona sólo sobre arrays
            # y objetos, y emitirá un error al intentar usarlo con una variable de un tipo diferente de datos o una variable no inicializada.
            foreach ($respuesta as $row => $item) {
                $nameGroup = Crud::getNameModel($item["id_group"], "groups"); //Se obtiene el nombre del grupo al que pertenece el estudiante
                $nameCarrer = Crud::getNameModel($item["id_career"], "carrers"); //Se obtiene el nombre de la carrera a la que pertenece el estudiante.
                $setTeacher = Crud::setTeacherModel($item["id"]); //Se pasa como parametro el id del estudiante, y segun el grupo a que pertenece,
                //se le asigna el profesor que le corresponde, para posteriormente mostrarlo en la tabla.
                echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                        <td>' . $nameGroup["name"] . '</td>
                        <td>' . $nameCarrer["name"] . '</td>
                        <td>' . $setTeacher["teacher"] . '</td>
                        <td><img class="materialboxed" data-caption="'.$item["name"].'" src="images/' . $item["photo"] . '" width="100px;"/></td>
                        <td>
                            <a id="edit_student" data-id="' . $item['id'] . '" class="modal-trigger " href="#editStudent"><button class="btn btn-info">Edit<i class="material-icons prefix blue-text">edit</i></button></a>
                            <a id="delete_student" data-id="' . $item['id'] . '" href="javascript:void(0)"><button class="btn btn-danger">Delete<i class="material-icons prefix red-text">delete</i></button></a>
                        </td>
                    </tr>';
            }

        } elseif ($_SESSION["type"] == "supervisor") { //Si la sesion iniciada es un supervisor, este no podrá borrar ni modificar los datos de la bd
            //A los supervisores solo se les permiten verlos.
            $respuesta = Crud::showModel("students"); //Se obtienen los datos de la tabla estuadiantes y se mostraran en la vista.
            #El constructor foreach proporciona un modo sencillo de iterar sobre arrays. foreach funciona sólo sobre arrays
            # y objetos, y emitirá un error al intentar usarlo con una variable de un tipo diferente de datos o una variable no inicializada.
            foreach ($respuesta as $row => $item) {
                $nameGroup = Crud::getNameModel($item["id_group"], "groups"); //Se obtiene el nombre del grupo
                $nameCarrer = Crud::getNameModel($item["id_career"], "carrers"); //Se obtiene el nombre de la carrera
                $setTeacher = Crud::setTeacherModel($item["id"]); //Se asigna el profesor al estudiante.
                echo '<tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $item["name"] . '</td>
                    <td>' . $nameGroup["name"] . '</td>
                    <td>' . $nameCarrer["name"] . '</td>
                    <td>' . $setTeacher["teacher"] . '</td>
                    <td><img src="images/' . $item["photo"] . '" width="100px;"/></td>
                        <td>
                    </tr>';
            }
        } else if ($_SESSION["type"] == "teacher") { //Si el que ingresó sesión fue un maestro:
            //Mostrar los alumnos según el maestro que ingresa $_SESSION id
            $respuesta = Crud::showStudentsTeacherModel();
            foreach ($respuesta as $row => $item) {
                $nameGroup = Crud::getNameModel($item["id_group"], "groups");
                $nameCarrer = Crud::getNameModel($item["id_career"], "carrers");
                echo '<tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $item["name"] . '</td>
                    <td>' . $nameGroup["name"] . '</td>
                    <td>' . $nameCarrer["name"] . '</td>
                    <td>' . $item["photo"] . '</td>
                    </tr>';
            }
        }
    }

    //Funcion que actualiza los datos del estudiante, recibe como parámetro el id del estudiante que se va a editar.
    public static function updateStudentController($idS)
    {
        if (isset($_POST["name"]) && isset($_POST["group"]) && isset($_POST["carrer"])) { //Si el usuario insertó los datos
            $datos = array("name" => $_POST["name"], "group" => $_POST["group"], "carrer" => $_POST["carrer"]); //Se pasan los datos en un arreglo
            $respuesta = new Crud(); //Se crea una instancia de la clase
            $r = $respuesta->updateStudentModel("students", $datos, $idS); //Se llama el modelo que actualiza el estudiante y recibe como parámetro
            //la tabla que será actualizada, con qué datos y el id del estudiante a actualizar.
            if ($r) { //Si la modificación se realizó con éxito, el sistema envía un mensaje de éxito al usuario.
                echo "<script>
                            swal({
                              type:'success',
                              title: 'Student updated successfuly!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=alumnos';
                                tr.hide();
                             }
                            );
                          </script>";
            } else {
                //De lo contrario, el sistema envía un mensaje de que el alumno no se pudo actualizar.
                echo "<script>
                            swal({
                              type:'error',
                              title: 'Could not update student!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=alumnos';
                                tr.hide();
                             });
                        </script>";
            }

        }
    }

    public static function updateUnitController($idU)
    {
        if (isset($_POST["name"]) && isset($_POST["startDate"]) && isset($_POST["endDate"])) { //Si el usuario insertó los datos
            $datos = array("name" => $_POST["name"], "start_date" => $_POST["startDate"], "end_date" => $_POST["endDate"]); //Se pasan los datos en un arreglo
            $respuesta = new Crud(); //Se crea una instancia de la clase
            $r = $respuesta->updateUnitModel("units", $datos, $idU); //Se llama el modelo que actualiza el estudiante y recibe como parámetro
            //la tabla que será actualizada, con qué datos y el id del estudiante a actualizar.
            if ($r) { //Si la modificación se realizó con éxito, el sistema envía un mensaje de éxito al usuario.
                echo "<script>
                            swal({
                              type:'success',
                              title: 'Unit updated successfuly!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=unidades';
                                tr.hide();
                             }
                            );
                          </script>";
            } else {
                //De lo contrario, el sistema envía un mensaje de que el alumno no se pudo actualizar.
                echo "<script>
                            swal({
                              type:'error',
                              title: 'Could not update unit!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=unidades';
                                tr.hide();
                             });
                        </script>";
            }

        }
    }

    //Funcion que sirve para actualizar los maestros que se han modificado
    public static function updateTeacherController($idT)
    {
        if (isset($_POST["name"]) && isset($_POST["user"]) && isset($_POST["password"])) { //Si se ingresaron los datos:

            $datos = array("name" => $_POST["name"], "user" => $_POST["user"], "password" => $_POST["password"]); //Los datos se pasan como
            //parte de un array asociativo que será utilizado por el modelo.
            $respuesta = new Crud(); //Se crea una instancia de la clase y
            $r = $respuesta->updateTeacherModel("users", $datos, $idT); //Se llama al modelo, se le pasa la tabla donde se hará la actualización,
            //en este caso es la de usuario (empleados, maestros, supervisores) y se modifican los datos
            if ($r) {
                //Si la modificacion fue un exito, el sistema envia un mensaje notificando al usuario.
                echo "<script>
                            swal({
                              type:'success',
                              title: 'Teacher updated successfuly!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=maestros';
                                tr.hide();
                             }
                            );
                          </script>";
            } else {
                //Si no fue así, el sistema envia un mensaje acerca del error ocurrido.
                echo "<script>
                            swal({
                              type:'error',
                              title: 'Could not update teacher!',
                              showConfirmButton: false,
                              timer:1500
                            },
                            function () {
                                window.location.href = 'index.php?action=maestros';
                                tr.hide();
                             });
                        </script>";
            }

        }
    }

    public static function insertTeacherController()
    {
        if (isset($_POST["name"])) { //Si el usuario ingreso los datos en el formulario
            $fullName = $_POST["name"] . " " . $_POST["last_name"]; //Se concatena el nombre que ingreso el usuario.
            $datos = array("name" => $fullName, "user" => $_POST["user"], "password" => $_POST["password"]); //Se guarda en
            //un arreglo asociativo estos datos.
            $respuesta = Crud::insertTeacherModel($datos); //Se pasan como parámetro los datos al modelo para que sepa que se va a insertar.
            //Valiación de la respuesta del modelo para ver si se hizo la insercion correctamente.
            //Si la actualizacion se realizo, se despliega un mensaje de confirmación
            if ($respuesta) {
                echo "<script>
                    swal({
                      type:'success',
                      title: 'Teacher saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=maestros';
                        tr.hide();
                     });
              </script>";
            } else {
                //Si la actulizacón no se pudo llevar a cabo, se notifica al usuario.
                echo "<script>
                    swal({
                      type:'error',
                      title: 'No changes saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=maestros';
                        tr.hide();
                     });
                </script>";
            }

        }
    }


    //Esta funcion sirve para mostrar los estudiantes que existen en el grupo que se recibe por metodo GET
    public static function showStudentsByGroupController()
    {
        if (isset($_GET["idGrupo"])) { //Si se ha mandado como parametro GET el id del grupo entonces
            $idGroup = $_GET["idGrupo"]; //Se guarda en una variable
            $respuesta = Crud::showStudentsByGroupModel($idGroup); //Esta variable se le pasa como parámetro a la función del modelo
            //que se encarga de mostrar los estudiantes por grupo.

            if ($_SESSION["type"] == "superadmin") { //Si la sesion es de un administrador, se mostraran todos los datos de los alumnos
                //tambien podrá editar y eliminar
                foreach ($respuesta as $row => $item) {
                    $nameGroup = Crud::getNameModel($item["id_group"], "groups"); //Se obtiene el nombre de la carrera
                    $nameCarrer = Crud::getNameModel($item["id_career"], "carrers"); //Se obtiene el nombre del grupo
                    $setTeacher = Crud::setTeacherModel($item["id"]); //Se le asigna un teacher segun el grupo para que pueda ser mostrado en la vist
                    //un nombre del maestro.
                    echo '<tr>
                            <td>' . $item["id"] . '</td>
                            <td>' . $item["name"] . '</td>
                            <td>' . $nameGroup["name"] . '</td>
                            <td>' . $nameCarrer["name"] . '</td>
                            <td>' . $setTeacher["teacher"] . '</td>
                            <td><img class="materialboxed" src="images/' . $item["photo"] . '" width="100px;"/></td>
                            <td>
                                <a id="edit_student" data-id="' . $item['id'] . '" class="modal-trigger " href="#editStudent"><button class="btn btn-info">Edit<i class="material-icons prefix blue-text">edit</i></button></a>
                                <a id="delete_student" data-id="' . $item['id'] . '" href="javascript:void(0)"><button class="btn btn-danger">Delete<i class="material-icons prefix red-text">delete</i></button></a>
                            </td>
                        </tr>';
                }

            } elseif ($_SESSION["type"] == "supervisor") { //Si la sesion es de un supervisor, se le mostraran los grupos pero de modo
                //solo vista, en el que no puede editar ni eliminar.
                foreach ($respuesta as $row => $item) {
                    $nameGroup = Crud::getNameModel($item["id_group"], "groups"); //Se obtiene nombre del grupo
                    $nameCarrer = Crud::getNameModel($item["id_career"], "carrers");//Se obtiene nombre de la carrera
                    $setTeacher = Crud::setTeacherModel($item["id"]); //Se le asigna un maestro al alumno, segun el grupo al que pertenece,
                    //esto se hace solamente para poder mostrar un maestro en la vista de alumnos
                    echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                        <td>' . $nameGroup["name"] . '</td>
                        <td>' . $nameCarrer["name"] . '</td>
                        <td>' . $setTeacher["teacher"] . '</td>
                        <td><img src="images/' . $item["photo"] . '" width="100px;"/></td>
                        </tr>';
                }
            } else if ($_SESSION["type"] == "teacher") { //Si quien accede a ver un grupo es un maestro, no se le mostrarán todos los grupols
                //ni quien inparte las clases, tampoco podrá modificar ni eliminar, solo tendrá oportunidad de ver los grupos a los que él
                //o ella dan clase.
                foreach ($respuesta as $row => $item) {
                    $nameGroup = Crud::getNameModel($item["id_group"], "groups");
                    $nameCarrer = Crud::getNameModel($item["id_career"], "carrers");
                    echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                        <td>' . $nameGroup["name"] . '</td>
                        <td>' . $nameCarrer["name"] . '</td>
                        <td><img src="images/' . $item["photo"] . '" width="100px;"/></td>
                        </tr>';
                }
            }
        }
    }

    //Funcion que sirve para agregar nuevos estudiantes al sistema.
    public static function insertStudentController()
    {
        if (isset($_POST["name"])) { //Si el usuario ingresó los datos del formulario.
            $contador = Crud::lastId("students"); //Se obtiene el ultimo id de la tabla de alumnos
            $contador = (int)$contador["id"] + 1; //Se le suma uno al id para asignar como nuevo id al nombre de la imagen
            $nombre_imagen = (string)$contador . "_" . $_FILES["imagen"]["name"]; //Nombre de la imagen concatenando un contador y el nombre del archivo.

            if (($nombre_imagen == !NULL) && ($_FILES["imagen"]["size"] < 200000)) {
                if ($_FILES["imagen"]["type"] == "image/jpeg"
                    || $_FILES["imagen"]["type"] == "image/jpg"
                    || $_FILES["imagen"]["type"] == "image/png") {
                    //$directorio=$_SERVER['DOCUMENT ROOT']."/intranet/uploads/"; //Ruta donde se guardaran la imagenes que subamos
                    $directorio = "images/";
                    //Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
                    move_uploaded_file($_FILES["imagen"]["tmp_name"], $directorio . $nombre_imagen);
                } else {
                    //Si no se cumple el formato
                    echo "<script>swal({title: 'This photo is not permited', text: 'Please choose another one', type:'error'}); </script>";
                }
            } else {
                if ($nombre_imagen == !NULL) {
                    echo "<script>swal({title: 'The photo is too heavy', text: 'Please choose another one.', type:'error'}); </script>";
                }
            }

            $fullName = $_POST["name"] . " " . $_POST["last_name"]; //Se concatena el nombre que se ingresó
            $datos = array("id" => $_POST["registration"], "name" => $fullName, "group" => $_POST["group"], "carrer" => $_POST["carrer"], "photo" => $nombre_imagen);
            $respuesta = Crud::insertStudentModel($datos); //Se mandan los datos como parámetro al modelo
            //Valiación de la respuesta, si se ejecuto la insercion entonces se proyecta un mensaje de éxito.
            if ($respuesta) {
                echo "<script>
                    swal({
                      type:'success',
                      title: 'Student saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=alumnos';
                        tr.hide();
                     });
              </script>";
            } else { //De lo contrario se depliega un mensaje sobre el error.
                echo "<script>
                    swal({
                      type:'error',
                      title: 'Not saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=alumnos';
                        tr.hide();
                     });
                </script>";
            }

        }
    }


    //Funcion utilizada para obtener la información del estudiante que se estará recibiendo como parámetro en el modelo.
    public static function getStudentInfo()
    {
        $matricula = (string)$_POST["registration"]; //Se obtiene la matricula de la que se desea obtener la infomación
        $student = Crud::getStudentInfoModel($matricula); //Se llama al modelo parándole como parámetro, la matrícula del estudiante.
        $group = Crud::getGroup($student["id_group"]); //Se obtiene el grupo
        $career = Crud::getCareer($student["id_career"]); //Se obtiene la carrera
        $teacher = Crud::getGroupTeacher($group["id_teacher"]); //Se obtiene el maestro del alumno
        if ($student != "" && isset($group) && isset($career) && isset($teacher)) { //Si los datos que se devoldieron del modelo, son correctos
            //Se redirige a una pagina con todos los datos que se necesitan, enviandolos por metodo GET.
            echo "<script> window.location.href='index.php?stdntn=" . $student["id"] . "&group=" . $group["name"] .
                "&career=" . $career["name"] . "&teacher=" . $teacher["name"] . "&img=" . $student["photo"] . "'</script>";
        } else { //Si no, se envia un error
            echo "<script> window.location.href='index.php?stdntn=error'</script>";
        }

    }

    //Funcion que obtiene las actividades que estan contenidas en la base de datos y llena un select
    public static function getActivitiesController()
    {
        $respuesta = Crud::showModel("activities"); //Muestra las entradas de la base de datos en la tabla "activities"
        foreach ($respuesta as $row => $item) { //imprime el nombre como opcion y el id será el valor del campo que se seleccione
            echo '<option value="' . $item["id"] . '" >' . $item["name"] . '</option>';
        }
    }

    //Funcion que agrega nuevas sesiones según los usuarios vayan ingresando
    public static function insertSessionController()
    {
        $fechayhoraActual = date("Y-m-d H"); //Se toma la fecha y hora del sistema, ojo, solo la hora, no los minutos ni segundos.
        $ultimo = Crud::lastId("sessions"); //Se obtiene el ultimo registro de la tabla de sesiones
        $fechayHoraUltimo = $ultimo["today_date"] . $ultimo["start_time"]; //Se concatena la fecha y hora del ultimo registro que existe en la tabla.
        $fechayHoraUltimo = strtotime($fechayHoraUltimo); //Se hace un casteo de la hora en string a time.
        $fechayHoraUltimo = date('Y-m-d H', $fechayHoraUltimo); //Se le aplica el formato deseado a dicha fecha y hora.

        if ($fechayHoraUltimo == $fechayhoraActual) {//Si la fecha y hora del sistema es igual a la fecha y hora del ultimo registro.
            //O sea que si la persona que acaba de ingresar lo hizo en la misma sesión del ultimo registro.
            $id = (int)$ultimo["id"]; //Entonces el id de ese registro será el mismo id de sesion que la anterior
        } else {
            $id = (int)$ultimo["id"] + 1; //De lo contrario, se le asigna un id nuevo de sesion
        }

        $fecha= date("Y-m-d"); //Solo fecha actual
        $units= Crud::showModel("units");
        foreach ($units as $row=>$item){
            $fechaInicio = $item["start_date"];
            $fechaFin = $item["end_date"];
            if ($fechaInicio<= $fecha && $fechayHoraUltimo<=$fechaFin){
                $unit=$item["id"];
            } else{
                $unit=0;
            }
        }

        if (isset($_POST["insertSession"])) { //Si se ha presionado el boton de insertar sesion
            $checkIfRegistered = Crud::checkIfRegistered($_POST["studentN"]); //Se verfica que el usuario no se haya registrado en la misma sesión
            if ((integer)$checkIfRegistered["cnt"] === 0) { //Si no se ha registrado en la misma sesion
                $idG = Crud::getIdModel($_POST["group"], "groups"); //Se obtiene el id del grupo al que pertenece
                $idT = Crud::getIdModel($_POST["teacher"], "users"); //Y el id del maestro, para agregarlos a la sesión.
                //Los datos obtenidos del formulario y los modelos, s guardan en un array asociativo, este array se pasará mas tarde como parámetro
                //a la funcion que se encarga de insertar la sesion a traves del modelo.
                $datos = array("id" => $id, "id_student" => $_POST["studentN"], "teacher" => $idT["id"], "group" => $idG["id"],
                    "activity" => $_POST["activity"], "start_time" => $_POST["time"], "tdate" => date("Y-m-d"), "unit"=>$unit);
                $respuesta = Crud::insertSessionModel($datos); //Se llama al modelo y se pasa como parámetro el arreglo que contiene los datos
                //echo "<script>console.log('Respuesta:".$respuesta."')</script>";
                if ($respuesta) {//Si la insercion de la nueva sesion se realizo correctamente, el sistema enviará un mensaje de exito.
                    echo "<script>
                        swal({
                          type:'success',
                          title: 'Session saved!',
                          showConfirmButton: false,
                          timer:1500
                        },
                        function () {
                            window.location.href = 'index.php';
                            tr.hide();
                         });
                    </script>";
                } else { //De lo contrario el mensaje será de error
                    echo "<script>
                        swal({
                          type:'error',
                          title: 'Session not saved!',
                          showConfirmButton: true,
                          //timer:1500
                        },
                        function () {
                            window.location.href = 'index.php';
                            tr.hide();
                         });
                </script>";
                }
            }else{ //Si se ha encontrado que el usuario que intenta registrar una nueva sesion, ya se habia registrado durante esa sesion
                //Se imprime un mensaje de que el usuario ya ha sido registrado en esa sesión.
                echo "<script>console.log('".$checkIfRegistered["cnt"]."')</script>";
                echo "<script>
                    swal({
                      type:'error',
                      title: 'Student already registered in the current session!',
                      showConfirmButton: true,
                      //timer:1500
                    },
                    function () {
                        window.location.href = 'index.php';
                        tr.hide();
                     });
                </script>";
            }
        }
    }

    //Funcion que controla todos los aspectos acerca de la hora de entrada de una sesion
    public static function entrySessionController()
    {
        $hora = date('H') . ":00:00"; //inicializo la hora en la que se encuentra la sesión
        $limite = strtotime('+11 minute', strtotime($hora)); //10 minutos de limite de entrada
        $limite = date('H:i:s', $limite); //Se le agrega formato a la hora limite
        $horaActual = date("H:i:s"); //Se obtiene la hora actual con minutos y segundos
        if ($horaActual <= $limite && $horaActual >= $hora) { //Si la hora en la que desea entrar el usuario, no se ha pasado del limite
            //y la hora en la que desea ingresar es mayor a la hora que se inicializo al principio entonces..
            $studentN = $_GET["stdntn"]; //Se obtiene la matricula
            $group = $_GET["group"]; //Se obtiene el grupo
            $career = $_GET["career"]; //Se obtiene la carrera
            $teacher = $_GET["teacher"]; //Se obtiene el maestro
            $image = $_GET["img"]; //Y se obtiene la ruta de la imagen.
            //Con cada uno de los datos se despliega un formulario que muestra los datos del alumno, y brinda la posibilidad de escoger
            //la actividad que el alumno va a realizar
            echo '<div class="row center-align">
                    <div class="col s6">
                        <img src="images/' . $image . '" style="width: 200px;" alt="">
                    </div>
                    <div class="col s6">
                        <form method="post" enctype="multipart/form-data">
                            <div class="input-field col s6">
                                <input id="time" type="text" class="validate" name="time" value="' . $horaActual . '"  required>
                                <label for="time">Time</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="studentN" type="text" class="validate" name="studentN" value="' . $studentN . '"  required>
                                <label for="studentN">Student Number</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="group" type="text" class="validate" name="group" value="' . $group . '"  required>
                                <label for="group">Group</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="teacher" type="text" class="validate" name="teacher" value="' . $teacher . '"  required>
                                <label for="teacher">Teacher</label>
                            </div>
                            <div class="input-field col s2">
                                <input id="career" type="text" class="validate" name="career" value="' . $career . '" required>
                                <label for="career">Career</label>
                            </div>
                            <div class="input-field col s10">
                                <select name="activity" required>
                                    <option value="" disabled selected>Choose your activity</option>';
            self::getActivitiesController(); //Se utiliza el controller que manda las actividades como un select.
            echo '
                                </select>
                                <label>Activity</label>
                            </div>
                                <button name="insertSession" class="waves-effect waves-light btn green darken-1" type="submit">Join</button>
                        </form>';
            self::insertSessionController(); //Se utiliza el controller para hacer insercion de la nueva sesion
        } else { //Si el tiempo no esta dentro del limite, despliega un mensaje de que no se puede ingresar a la sesión aun.
            echo "<script>
                swal({
                  type:'error',
                  title: 'It is not time to join the session!',
                  showConfirmButton: false,
                  timer:2000
                });
           </script>";
        }
    }

    public static function endSessionController($matricula)
    {
        $fechayhoraActual = date("Y-m-d H"); //Fecha y hora actuales
        $ultimo = Crud::lastId("sessions"); //Ultimo registro (sesion actual)
        $fechayHoraUltimo = $ultimo["today_date"] . $ultimo["start_time"]; //Hora y fecha de la sesion actual
        $fechayHoraUltimo = strtotime($fechayHoraUltimo); //Hora y fecha de la sesion actual
        $fechayHoraUltimo = date('Y-m-d H', $fechayHoraUltimo); //Hora y fecha de la sesion actual
        $last=Crud::getLastSession($matricula); //La ultima sesion registrada con esa matricula
        if ($fechayHoraUltimo == $fechayhoraActual) { //Si se encuentra en la misma sesion
            $id = (int)$ultimo["id"]; //Id de la sesion
        } else{
            $id=$last["id"]; //Id de la sesion
        }
        $hora = date('H') . ":00:00"; //inicializo la hora en la que se encuentra la sesión
        $limite = strtotime('+55 minute', strtotime($hora)); //55 minutos de limite de salida
        //$limite = date('H:i:s', $limite);
        $horaActual = new DateTime(date("Y-m-d H:i:s"));
        $horaEntrada= new DateTime($last["today_date"]. $last["start_time"]);
        $difference = $horaEntrada->diff($horaActual);
        $minutes = $difference->d * 24 * 60;
        $minutes += $difference->h * 60;
        $minutes += $difference->i;
        $hours = floor($minutes / 55);
        echo "<script>console.log('". $minutes ."')</script>";
        echo "<script>console.log('". $hours ."')</script>";
        if ($minutes < 55) {//Minutos limite para salir
            //Si los minutos son menores a 55 y el usuario intenta salir, se despliega un mensaje que dice que aun no es hora de irse.
            echo "<script>console.log('". $minutes ."')</script>";
            echo "<script>
                swal({
                  type:'error',
                  title: 'You cannot leave yet',
                  showConfirmButton: false,
                  timer:3000
                });
            </script>";
        } else { //Si ya es hora de que el usuario pueda salir
            $horaActual=date("H:i:s"); //Se obtiene la hora de salida
            //$hours = (int)$minutes / 55;

            $respuesta = Crud::endSessionModel($id, $matricula, $horaActual, $hours); //Se hace la modificacion en la base de datos
            //Con los elementos que recibe como parámetros, el id de la sesion, la matricula del alumno y la hora de salida.
            if ($respuesta) { //Si el registro fue exitoso se despliega un mensaje que diga que se ha registrado la salida del usuario.
                echo "<script>
                swal({
                  type:'success',
                  title: 'End time registered!',
                  showConfirmButton: false,
                  timer:3000
                });
            </script>";
            } else { //Si no fue exitosa la modificacion se despliega un mensaje que lo indica
                echo "<script>
                swal({
                  type:'error',
                  title: 'The end time was not registered!',
                  showConfirmButton: false,
                  timer:3000
                });
            </script>";
            }
        }

    }

    //Funcion que le muestra a cada maestro, las sesiones que han tenido sus alumnos.
    public static function showSessionsByTeacherController()
    {
        $idTeacher = $_GET["idTeacher"];//Se obtiene el id del profesor
        $respuesta = Crud::showSessionByTeacherModel($idTeacher); //Se llama al modelo que mostrara las sesiones segun el id
        //que recibe como parámetro.
        foreach ($respuesta as $row => $item) { //por cada session que devuelva el modelo
            $student = Crud::getStudentInfoModel($item["id_student"]);//Se va a obtener el alumno
            $nameGroup = Crud::getNameModel($item["id_group"], "groups"); //SE obtiene el grupo
            $nameCareer = Crud::getNameModel($student["id_career"], "carrers"); //Se obtiene la carrera
            //$nameStudent = Crud::getNameModel($item["id_student"], "students"); //se obtiene el nombre del alumno
            $nameActivity = Crud::getNameModel($item["id_activity"], "activities"); //Se obtiene la actividad
            //Y se desplegan cada uno de ellos en una tabla.

            echo '<tr>
                    <td>' . $item["id_student"] . '</td>
                    <td>' . $student["name"] . '</td>
                    <td>' . $nameGroup["name"] . '</td>
                    <td>' . $nameCareer["name"] . '</td>
                    <td>' . $item["totalHours"] . '</td>
                    <td><a href="index.php?action=verSesionesAlumno&idAlumno=' . $item["id_student"] . '">
                        <button class="btn btn-info">More Info<i class="material-icons prefix white-text left">visibility</i></button>
                        </a></td>
                </tr>';
        }
    }


    public static function showTeachersForSessionsController()
    {
        $teachers = Crud::showEmployeeModel("users", "teacher"); //Se llama al modelo que mostrara las sesiones segun el id
        //que recibe como parámetro.
        foreach ($teachers as $row => $item) { //por cada session que devuelva el modelo
            $group = Crud::getGroupByTeacherId($item["id"], "groups"); //SE obtiene el grupo

            //Y se desplegan cada uno de ellos en una tabla.

            echo '<tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $item["name"] . '</td>
                    <td><a href="index.php?action=alumnosSesion&idTeacher='. $item["id"] .'">
                        <button class="btn btn-info">See students & sessions<i class="material-icons prefix white-text left">visibility</i></button>
                        </a></td>
                </tr>';
        }
    }

    public static function showSessionsFromStudentController()
    {
        $idTeacher = $_SESSION["id"];//Se obtiene el id del profesor
        $idStudent = $_GET["idAlumno"];
        $respuesta = Crud::showSessionFromStudentModel($idStudent); //Se llama al modelo que mostrara las sesiones segun el id
        //que recibe como parámetro.
        foreach ($respuesta as $row => $item) { //por cada session que devuelva el modelo
            $student = Crud::getStudentInfoModel($idStudent);//Se va a obtener el alumno
            $nameUnit = Crud::getNameModel($item["unit"], "units");
            $nameGroup = Crud::getNameModel($item["id_group"], "groups"); //SE obtiene el grupo
            $nameCareer = Crud::getNameModel($student["id_career"], "carrers"); //Se obtiene la carrera
            //$nameStudent = Crud::getNameModel($item["id_student"], "students"); //se obtiene el nombre del alumno
            $nameActivity = Crud::getNameModel($item["id_activity"], "activities"); //Se obtiene la actividad
            //Y se desplegan cada uno de ellos en una tabla.

            echo '<tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $item["id_student"] . '</td>
                    <td>' . $student["name"] . '</td>
                    <td>' . $nameGroup["name"] . '</td>
                    <td>' . $nameCareer["name"] . '</td>
                    <td>' . $nameActivity["name"] . '</td>
                    <td>' . $item["today_date"] . '</td>
                    <td>' . $item["start_time"] . '</td>
                    <td>' . $item["end_time"] . '</td>
                    <td>' . $nameUnit["name"] . '</td>
                    <td>' . $item["hours"] . '</td>
                </tr>';
        }
    }

    //Funcion que sirve para mostar una lista con los estudiantes que estan en la sesion actual
    public static function sessionsListController(){
        if (isset($_GET["idSesion"])) { //Si se ha presionado el botón de "ver grupo" el controller llama la funcion del modelo que filtra
            //los alumnos a través del id del grupo que se eligió para ver más.
            $idSession = $_GET["idSesion"]; //Se obtiene el id de la sesion del cual se presionó el botón de "see session"  y se almacena en una variable
            $respuesta= Crud::showSessionModel($idSession);//La variable obtenida se utiliza en la funcion de mostrar los estudiante segun el grupo
            //al que pertenecen.

            foreach ($respuesta as $row => $item) { //Por cada sesion se mostrará el id, el numero de estudiantes que hay, la fecha de la sesion
                $student= Crud::getNameModel($item["id_student"], "students");
                $teacher= Crud::getNameModel($item["id_teacher"], "users");
                $group= Crud::getNameModel($item["id_group"], "groups");
                $activity= Crud::getNameModel($item["id_activity"], "activities");
                echo '<tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $student["name"] . '</td>
                    <td>' . $teacher["name"] . '</td>
                    <td>' . $group["name"] . '</td>
                    <td>' . $activity["name"] . '</td>
                    <td>' . $item["today_date"] . '</td>
                    <td>' . $item["start_time"] . '</td>
                    <td>' . $item["end_time"] . '</td>
                  </tr>';
            }
        } else {
            $list = Crud::studentsInSessionModel(); //Se llama al modelo para que muestre los estudiantes
            foreach ($list as $row => $item) { //Por cada sesion se mostrará el id, el numero de estudiantes que hay, la fecha de la sesion
                echo '<tr>
                    <td>' . $item["sess_id"] . '</td>
                    <td>' . $item["cnt"] . '</td>
                    <td>' . $item["today_date"] . '</td>
                     <td><a href="index.php?action=verSesion&idSesion=' . $item["sess_id"] . '">
                        <button class="btn btn-info">See this session<i class="material-icons prefix white-text left">visibility</i></button>
                        </a></td>
                  </tr>';
            }
        }
    }

    public static function totalStudentsInSession(){
        $idLastSession = Crud::lastId("sessions");
        $total = Crud::totalStudentsInSession($idLastSession["id"]);

        echo $total["total"];
    }

    public static function getDeletedSessions(){
        $sessions = Crud::getDeletedSessions();

        foreach ($sessions as $row => $item) { //Por cada sesion se mostrará el id, el numero de estudiantes que hay, la fecha de la sesion
            $teacher = Crud::getNameModel($item["id_teacher"], "users");
            $group = Crud::getNameModel($item["id_group"], "groups");
            echo '<tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $item["id_session"] . '</td>
                    <td>' . $item["id_student"] . '</td>
                    <td>' . $teacher["name"] . '</td>
                    <td>' . $group["name"] . '</td>
                    <td>' . $item["date"] . '</td>
                  </tr>';
        }
    }

    public static function showUnitsController()
    {
        //Si la sesión ingresada es un superadmin, se muestran todos los grupos, los maestros que estan a cargo y se
        //puede editar y eliminar.
        if ($_SESSION["type"] == "superadmin") {
            $respuesta = Crud::showModel("units");
            #El constructor foreach proporciona un modo sencillo de iterar sobre arrays. foreach funciona sólo sobre arrays
            # y objetos, y emitirá un error al intentar usarlo con una variable de un tipo diferente de datos o una variable no inicializada.
            foreach ($respuesta as $row => $item) {
                echo '<tr>
                        <td>' . $item["id"] . '</td>
                        <td>' . $item["name"] . '</td>
                        <td>' . $item["start_date"] . '</td>
                        <td>' . $item["end_date"] . '</td>
                        <td>
                            <a id="edit_unit" data-id="' . $item['id'] . '" class="modal-trigger " href="#editUnit"><button class="btn btn-info teal darken-1">Edit<i class="material-icons prefix blue-text left">edit</i></button></a>
                            <a id="delete_unit" data-id="' . $item['id'] . '" href="javascript:void(0)"><button class="btn btn-danger teal darken-1">Delete<i class="material-icons prefix red-text left">delete</i></button></a>
                        </td>
                    </tr>';
            }
            if (isset($_GET["idGrupo"])) { //Si se ha presionado el botón de "ver grupo" el controller llama la funcion del modelo que filtra
                //los alumnos a través del id del grupo que se eligió para ver más.
                $idGrupo = $_GET["idGrupo"]; //Se obtiene el id del grupo del cual se presionó el botón de "see group"  y se almacena en una variable
                self::showStudentsByGroupController($idGrupo);//La variable obtenida se utiliza en la funcion de mostrar los estudiante segun el grupo
                //al que pertenecen.
            }
        }
    }

    public static function insertUnitController()
    {
        if (isset($_POST["name"]) && isset($_POST["startDate"])) { //Si el usuario llenó los datos para agregar un nuevo grupo
            $datos = array("name" => $_POST["name"], "start_date" => $_POST["startDate"], "end_date" => $_POST["endDate"]); //Se guardan los datos en un arreglo
            $respuesta = Crud::insertUnitModel($datos); //Estos datos se pasan al modelo que se encargará de insertarlos en la base de
            //datos, los toma como parámetro y los inserta.
            if ($respuesta) { //Si se realizó la inserción se muestra el mensaje de que se ha agregado correctamente el grupo
                echo "<script>
                    swal({
                      type:'success',
                      title: 'Unit saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=unidades';
                        tr.hide();
                     });
              </script>";
            } else {//Si la inserción fallo, el grupo no se inserto en la bd y se le informa al usuario.
                echo "<script>
                    swal({
                      type:'error',
                      title: 'No changes saved!',
                      showConfirmButton: false,
                      timer:1500
                    },
                    function () {
                        window.location.href = 'index.php?action=unidades';
                        tr.hide();
                     });
                </script>";
            }

        }
    }

}

?>