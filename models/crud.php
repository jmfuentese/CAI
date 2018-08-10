<?php
	require_once("conexion.php"); //Se llama al archivo de conexion para que se puedan llevar a cabo las conexiones a la base de datos

	class Crud extends Conexion{ //Se crea la clase que extenderá de la clase Conexion
	    //Funcion que sirve para ingresar al sistema
        public static function loginModel($tabla, $datos){ //Se obtienen los parámetros (la tabla de usuarios y los datos que contienen
            //el usuario y contraseña ingresados)
            $statement = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE user = :us AND password = :pass"); //Se selecciona todo
            //en la tabla para saber si los datos coinciden
            $statement->bindParam(":us", $datos["user"], PDO::PARAM_STR); //Se utilizan los parámetros que recibe la funcion.
            $statement->bindParam(":pass", $datos["password"], PDO::PARAM_STR);
            $statement->execute();
            #fetch(): Obtiene una fila de un conjunto de resultados asociado al objeto PDOStatement.
            return $statement->fetch();
            $statement->close();
        }

        //Funcion que sirve para obtener el nombre de cualquier elemento en cualquier tabla
        public static function getNameModel($id, $table){ //Recibe como parámetro la tabla y el identificador del registro del cual deseamos
            //obtener el nombre
            $statement = Conexion::conectar()->prepare("SELECT name FROM $table WHERE id=:id"); //Se selecciona el nombre
            $statement->bindParam(":id", $id, PDO::PARAM_INT);  //Se utiliza el id que recibio como parámetro
            $statement->execute();

            return $statement->fetch();
        }

        public static function getGroupByTeacherId($idT, $table){ //Recibe como parámetro la tabla y el identificador del registro del cual deseamos
            //obtener el nombre
            $statement = Conexion::conectar()->prepare("SELECT * FROM $table WHERE id_teacher=:idT"); //Se selecciona el nombre
            $statement->bindParam(":idT", $idT, PDO::PARAM_INT);  //Se utiliza el id que recibio como parámetro
            $statement->execute();

            return $statement->fetch();
        }

        //Funcion que obtiene los datos de cualquier tabla que recibe como parámetro
        public static function showModel($table)
        {
            $statement = Conexion::conectar()->prepare("SELECT * FROM $table"); //Se hace la sentencia SQL donde se obtienen los datos
            $statement->execute();

            return $statement->fetchAll(); //Se retornan los datos obtenidos.
        }

        //Se utiliza la funcion showEmployeeModel para mostrar solo los empleados que dependiendo de su
        // tipo, si la columna está en 1 significa que es de ese tipo, por ejemplo un empleado que tiene un 1
        // en la columna teacher, significa que es maestro.
        public static function showEmployeeModel($table, $column)
        {
            $statement = Conexion::conectar()->prepare("SELECT * FROM $table WHERE $column = 1 ");
            $statement->execute();

            return $statement->fetchAll();
        }

        //AQUI

        //Funcion delete se encarga de borrar el registro que corresponde al id que recibe como parámetro, de la tabla
        //que recibe como parámetro.
        public static function deleteModel($id, $table){
            $statement = Conexion::conectar()->prepare("DELETE FROM $table WHERE id = :id"); //Sentencia SQL
            $statement->bindParam(":id", $id, PDO::PARAM_INT); //Utiliza el id que recibio como parámetro
            if ($statement->execute()){ //Devuelve un valor falso o verdadero
                return true;
            } else{
                return false;
            }
        }

        //Obtiene el id de cualquier registro de una tabla, a partir del nombre
        public static function getIdModel($name, $table){ //Recibe como parámetros el nombre y la tabla en la que se desea buscar
            $statement = Conexion::conectar()->prepare("SELECT id FROM $table WHERE name = :name"); //SEntencia SQL
            $statement->bindParam(":name", $name, PDO::PARAM_STR); //Se utilia el nombre
            $statement->execute(); //Se ejecuta

            return $statement->fetch(); //Se retorna el registro correpondiente
        }

        //Funcion encargada de insertar un nuevo grupo en la base de datos
        public static function insertGroupModel($datos){ //Recibe como parámetros los datos que va a insertar
            $statement = Conexion::conectar()->prepare(
                "INSERT INTO groups(name,id_teacher) 
                                VALUES (:name,:id_teacher)"); //Inserta los valores correspondientes en la tabla.
            $statement->bindParam(":name", $datos["level"], PDO::PARAM_STR);
            $statement->bindParam(":id_teacher", $datos["id_teacher"], PDO::PARAM_INT);
            if ($statement->execute()) { //Si la inserción se realizo correctamente, devuelve un true, de lo contrario, devuelve un false.
                return true;
            } else {
                return false;
            }
        }

        //Funcion que sirve para editar un grupo en la base de datos
        public static function updateGroupModel($table, $datos, $idG){ //Se toman los datos importante, la tabla donde se hara la modificacion
            //los datos correspondientes y el id del elemento al que se le aplicarán los cambios
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET name = :level, id_teacher = :teacherId WHERE id = :id");
            $stmt->bindParam(":level", $datos["level"], PDO::PARAM_STR);
            $stmt->bindParam(":teacherId", $datos["id_teacher"], PDO::PARAM_INT);
            $stmt->bindParam(":id", $idG, PDO::PARAM_INT);
            if($stmt->execute()){ //Devuelve verdadero o falso según sea el caso
                return true;
            }
            else{
                return false;
            }
        }

        //Funcion que sirve para editar una unidad en la base de datos
        public static function updateUnitModel($table, $datos, $idU){ //Se toman los datos importante, la tabla donde se hara la modificacion
            //los datos correspondientes y el id del elemento al que se le aplicarán los cambios
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET name = :name, start_date = :sD, end_date = :eD WHERE id = :id");
            $stmt->bindParam(":name", $datos["name"], PDO::PARAM_STR);
            $stmt->bindParam(":sD", $datos["start_date"], PDO::PARAM_STR);
            $stmt->bindParam(":eD", $datos["end_date"], PDO::PARAM_STR);
            $stmt->bindParam(":id", $idU, PDO::PARAM_INT);
            if($stmt->execute()){ //Devuelve verdadero o falso según sea el caso
                return true;
            }
            else{
                return false;
            }
        }

        //Funcion que sirve para actualizar un estudiante
        public static function updateStudentModel($table, $datos, $idS){
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET name = :name, id_group = :groupId, id_career = :careerId WHERE id = :id");
            //Se actualiza por medio de la sentencia SQL en la que se le pasan los datos que vienen del formulario.
            $stmt->bindParam(":name", $datos["name"], PDO::PARAM_STR); //Se utilizan los datos
            $stmt->bindParam(":groupId", $datos["group"], PDO::PARAM_INT);
            $stmt->bindParam(":careerId", $datos["carrer"], PDO::PARAM_INT);
            $stmt->bindParam(":id", $idS, PDO::PARAM_INT);

            if($stmt->execute()){ //Devuelve true si se realizo la actualizacion
                //y false si no se hizo con exito
                return true;
            }
            else{
                return false;
            }
        }

        //Funcion que sirve para actualizar un maestro
        public static function updateTeacherModel($table, $datos, $idT){ //Se obtiene la tabla, los datos a actualizar y el id del maestro
            //al que se le aplicarán los cambios
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET name = :name, user = :user, password = :password WHERE id = :id"); //Se prepara
            // la sentencia SQL y se utilizan los datos del arreglo que recibe como parámetro.
            $stmt->bindParam(":name", $datos["name"], PDO::PARAM_STR);
            $stmt->bindParam(":user", $datos["user"], PDO::PARAM_STR);
            $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
            $stmt->bindParam(":id", $idT, PDO::PARAM_INT);
            //Si se actualizo correctamente se devuelve un true, si no, se devuelve un false.
            if($stmt->execute()){
                return true;
            }
            else{
                return false;
            }
        }

        //Funcion que muestra los grupos a un teacher.
        public static function showGroupTeacherModel()
        {
            $statement = Conexion::conectar()->prepare("SELECT * FROM groups WHERE id_teacher= :id");
            $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT); //Se utiliza el id del usuario que inicio sesion
            //para así saber qué maestro hay que tomar para mostrarle los grupos.
            $statement->execute();

            return $statement->fetchAll(); //Se devuelven todos los grupos que coincidan
        }

        //Funcion que inserta un nuevo estudiante en la base de datos.
        public static function insertStudentModel($datos){ //Recibe el array asociativo que será usado para inserar los datos que contiene
            $statement = Conexion::conectar()->prepare(
                "INSERT INTO students(id, name,id_group, id_career, photo) 
                                VALUES (:id, :name,:idG, :idC, :photo)");
            $statement->bindParam(":id", $datos["id"], PDO::PARAM_STR);
            $statement->bindParam(":name", $datos["name"], PDO::PARAM_STR);
            $statement->bindParam(":idG", $datos["group"], PDO::PARAM_INT);
            $statement->bindParam(":idC", $datos["carrer"], PDO::PARAM_INT);
            $statement->bindParam(":photo", $datos["photo"], PDO::PARAM_STR);
            if ($statement->execute()) {
                return true;
            } else { //Si la inserción no se realizó con éxito, devuelve falso.
                return false;
            }
        }

        //Funcion que agrega un nuevo maestro al sistema
        public static function insertTeacherModel($datos){  //Recibe el array asociativo que será usado para inserar los datos
            // que contiene dicho array
            $statement = Conexion::conectar()->prepare(
                "INSERT INTO users(name,user, password,teacher) 
                                VALUES (:name,:user, :password, 1)");
            $statement->bindParam(":name", $datos["name"], PDO::PARAM_STR);
            $statement->bindParam(":user", $datos["user"], PDO::PARAM_STR);
            $statement->bindParam(":password", $datos["password"], PDO::PARAM_STR);
            if ($statement->execute()) { //Si la inserción se hizo correctamente, entonces devuelve verdadero
                // de lo contrario retornará falso.
                return true;
            } else {
                return false;
            }
        }

        //Funcion que recibe como parametro el id de un estudiante y se le asigna un profesor.
        public static function setTeacherModel($idStudent){
            $statement= Conexion::conectar()->prepare("SELECT s.id, s.id_group, g.id_teacher, u.name as teacher FROM groups g, students s, users u 
                                                      WHERE s.id=$idStudent AND g.id=s.id_group AND g.id_teacher=u.id"); //Se utiliza el
            //id del estudiante, el id del grupo y el id del profesor. Deacuerdo al id del estudiante se le asocia un grupo y ese grupo
            //lo imparte un maestro asi que dicho maestro se le asocia también al alumno.
            $statement->execute();
            return $statement->fetch();
        }

        //Funcion que muestra los estudiantes a los que cada maestro da clase.
        public static function showStudentsTeacherModel()
        {
            $statement = Conexion::conectar()->prepare("SELECT s.id as id, s.name as name, s.id_career as id_career, s.id_group as id_group,
                                                        g.id_teacher, s.name as student FROM groups g, students s
                                                      WHERE g.id_teacher= :id AND s.id_group=g.id"); //Se seleccionan todos los datos del alumno
            //y se filtrn por el id_teacher del grupo.
            $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT); //Para identificar el id se utiliza el id de sesion que se ingreso.
            $statement->execute();

            return $statement->fetchAll();
        }

        //Funcion que muestra los estudiantes que pertenecen a un grupo
        public static function showStudentsByGroupModel($idGroup) //Recibe el id del grupo como parametro
        {
            $statement = Conexion::conectar()->prepare("SELECT * FROM students WHERE id_group = :id");
            $statement->bindParam(":id", $idGroup, PDO::PARAM_INT); //Se utiliza el id en la consulta para filtrar todos los estudiantes
            //que pertenecen al grupo con dicho id
            $statement->execute();

            return $statement->fetchAll();
        }

        //Obtiene el utlimo id de cualquier tabla
        public static function lastId($table){ //Recibe como parámeto la tabla de la que se quiere obtener el ultimo id
            $statement = Conexion::conectar()->prepare("SELECT * FROM $table ORDER BY id DESC limit 1"); //Se selecciona el id
            //haciendo un ordenamiento por id y tomando solo el primer registro de los que devuelve.
            $statement->execute();

            return $statement->fetch();
        }

        //Modelo que sirve para editar cualquier registro
        public static function editModel($id, $table){//Recibe el id del elemento en la tabla y la tabla a la que pertenece.
            $statement = Conexion::conectar()->prepare("SELECT * FROM $table WHERE id=:id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT); //Se selecciona todo lo de ese registro para poder editarlo poseriormente.
            $statement->execute();

            return $statement->fetch();
        }

        //Funcion que obtiene la información de un estudiante del cual se conoce su matricula
        public static function getStudentInfoModel($matr){ //SE pasa como parámetro la matricula del estudiante
            $statement = Conexion::conectar()->prepare("SELECT * FROM students WHERE id=:matr"); // Se selecciona toda la informacion del estudiante
            // al que pertenece dicha matricula
            $statement->bindParam(":matr", $matr, PDO::PARAM_STR); //Se utiliza la matricula que viene como parámetro
            $statement->execute(); //Se ejecuta la consulta

            return $statement->fetch(); //Se devuelve el resultado. Se utiliza fetch cuando la cosnsulta solo devuelve una fila y fetchAll cuando
            //devuelve varias filas
        }

        //Obtiene la informacion del grupo que recibe como parámetro
        public static function getGroup($id){ //Recibe un id de grupo y devuelve su información
            $statement = Conexion::conectar()->prepare("SELECT * FROM groups WHERE id=:id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch();
        }

        //Obtiene información sobre la carrera en la que coincide con el id que recibe como parámetro
        public static function getCareer($id){
            $statement = Conexion::conectar()->prepare("SELECT * FROM carrers WHERE id=:id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT); //Se filtra por el id que recibio como parametro
            $statement->execute();

            return $statement->fetch(); //Retorna los valores de una sola fila
        }

        //Funcion que devuelve toda la información de un usuario a través de su id, se utliza para obtener el grupo
        //al que pertenece un maestro, en el cual da clase.
        public static function getGroupTeacher($id){
            $statement = Conexion::conectar()->prepare("SELECT * FROM users WHERE id=:id"); //Se filtra por el id
            $statement->bindParam(":id", $id, PDO::PARAM_INT); //Se utiliza el id que recibe como parámetro
            $statement->execute();

            return $statement->fetch();
        }

        //Funcion que inserta (registra) una nueva sesion)
        public static function insertSessionModel($datos){
            //Inserta en la tabla de sessions una nueva sesion con los datos que obtiene del arreglo
            //asociativo que recibe como parámetro
            $statement = Conexion::conectar()->prepare("INSERT INTO sessions(id ,id_student,id_teacher, id_group, id_activity, today_date, start_time, unit) 
                                VALUES (:id,:idS,:idT, :idG, :idA, :tdate, :startime, :u)");
            $statement->bindParam(":id", $datos["id"], PDO::PARAM_INT);
            $statement->bindParam(":idS", $datos["id_student"], PDO::PARAM_INT);
            $statement->bindParam(":idT", $datos["teacher"], PDO::PARAM_INT);
            $statement->bindParam(":idG", $datos["group"], PDO::PARAM_INT);
            $statement->bindParam(":idA", $datos["activity"], PDO::PARAM_INT);
            $statement->bindParam(":tdate", $datos["tdate"], PDO::PARAM_STR);
            $statement->bindParam(":startime", $datos["start_time"], PDO::PARAM_STR);
            $statement->bindParam(":u", $datos["unit"], PDO::PARAM_INT);
            if ($statement->execute()) {
                return true;
            } else {
                return false;
            }
        }

        //Funcion que registra la salida del usuario
        public static function endSessionModel($id, $mat, $hora, $hours){ //Utiliza el id de la sesion, la matricula del alumno y la hora de salida
            $statement = Conexion::conectar()->prepare("UPDATE sessions SET end_time=:hora, hours = :h WHERE id_student=:mat AND id=:id AND end_time is NULL");
            //Se hace un update al registro de la tabla en la que la matricula y la sesion correspondan y se inserta la hora de salida.
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->bindParam(":mat", $mat, PDO::PARAM_INT);
            $statement->bindParam(":hora", $hora, PDO::PARAM_STR);
            $statement->bindParam(":h", $hours, PDO::PARAM_INT);
            if ($statement->execute()) { //Si la actualizacion se realizo con éxito, devuelve un true, o un fale en caso contrario.
                return true;
            } else {
                return false;
            }
        }

        public static function showSessionFromStudentModel($idS){ //Se recibe como parámetro el id del maestro
            $statement = Conexion::conectar()->prepare("SELECT * FROM sessions WHERE id_student = :idS");
            $statement->bindParam(":idS", $idS, PDO::PARAM_INT); //Se utiliza el id que recibio como parámetro, para filtrar los resultados.
            $statement->execute();

            return $statement->fetchAll();
        }

        //Funcion que muestra las sesiones de los alumnos que corresponden a un maestro
        public static function showSessionByTeacherModel($idT){ //Se recibe como parámetro el id del maestro
            $statement = Conexion::conectar()->prepare("SELECT id_student, id_teacher, id_group, SUM(hours) AS totalHours FROM sessions WHERE id_teacher = :idT GROUP BY id_student, id_group");
            $statement->bindParam(":idT", $idT, PDO::PARAM_INT); //Se utiliza el id que recibio como parámetro, para filtrar los resultados.
            $statement->execute();

            return $statement->fetchAll();
        }


        //Funcion que verifica si el usuario ya ingresó previamente en la misma sesión.
        public static function checkIfRegistered($idS){ //Toma el id del estudiante del que se va a verificar que no se haya registrado previamente.
            $statement = Conexion::conectar()->prepare("SELECT COUNT(*) AS cnt FROM sessions WHERE id_student=:idS AND end_time IS NULL ");
            $statement->bindParam(":idS", $idS, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch();
        }

        //Funcion que obtiene la utltima sesion en la que estuvo una matricula correspondiente
        public static function getLastSession($mat){ //Esta funcion ayuda a modificar este elemento si la sesion no fue cerrada
            $statement = Conexion::conectar()->prepare("SELECT * FROM sessions WHERE id_student=:mat ORDER BY id DESC limit 1");
            $statement->bindParam(":mat", $mat, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch();
        }

        //Funcion que obtiene cuantos estudiantes estan presentes en la sesion actual.
        public static function studentsInSessionModel(){
            //Esta funcion utiliza un select anidado
            $statement = Conexion::conectar()->prepare("SELECT id as sess_id,(SELECT today_date FROM `sessions` WHERE id = sess_id LIMIT 1) 
                                                          AS today_date, count(*) AS cnt FROM `sessions` GROUP BY id ");//ORDER BY today_date DESC
            $statement->execute();

            return $statement->fetchAll();
        }

        //Funcion que muestra los estudiantes que pertenecen a un grupo
        public static function showSessionModel($idSession) //Recibe el id del grupo como parametro
        {
            $statement = Conexion::conectar()->prepare("SELECT * FROM sessions WHERE id = :id");
            $statement->bindParam(":id", $idSession, PDO::PARAM_INT); //Se utiliza el id en la consulta para filtrar todos los estudiantes
            //que pertenecen al grupo con dicho id
            $statement->execute();

            return $statement->fetchAll();
        }

        public static function totalStudentsInSession($idS){

            $statement = Conexion::conectar()->prepare("SELECT COUNT(*) AS total FROM sessions WHERE id = :id");
            $statement->bindParam(":id", $idS, PDO::PARAM_INT); //Se utiliza el id en la consulta para contar todos los estudiantes
            //que pertenecen al grupo con dicho id
            $statement->execute();

            return $statement->fetch();
        }

        public static function setHours($idS, $startime, $hours, $idstudent){
            $statement = Conexion::conectar()->prepare("UPDATE sessions SET hours=:h WHERE id_student=:id AND start_time = :sT AND id=:idS");
            $statement->bindParam(":h", $hours, PDO::PARAM_INT);
            $statement->bindParam(":id", $idstudent, PDO::PARAM_INT); //Se utiliza el id en la consulta para contar todos los estudiantes
            //que pertenecen al grupo con dicho id
            $statement->bindParam(":sT", $startime, PDO::PARAM_STR);
            $statement->bindParam(":idS", $idS, PDO::PARAM_INT);
            if($statement->execute()){
                return true;
            } else {
                return false;
            }
        }

        public static function getDeletedSessions(){
            $statement = Conexion::conectar()->prepare("SELECT * FROM deleteHistory");
            $statement->execute();

            return $statement->fetchAll();
        }

        public static function insertUnitModel($datos){ //Recibe como parámetros los datos que va a insertar
            $statement = Conexion::conectar()->prepare(
                "INSERT INTO units(name,start_date, end_date) 
                                VALUES (:name,:sD, :eD)"); //Inserta los valores correspondientes en la tabla.
            $statement->bindParam(":name", $datos["name"], PDO::PARAM_STR);
            $statement->bindParam(":sD", $datos["start_date"], PDO::PARAM_STR);
            $statement->bindParam(":eD", $datos["end_date"], PDO::PARAM_STR);
            if ($statement->execute()) { //Si la inserción se realizo correctamente, devuelve un true, de lo contrario, devuelve un false.
                return true;
            } else {
                return false;
            }
        }

    }
?>