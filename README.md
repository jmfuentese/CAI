#Control de acceso de alumnos y sesiones para el Centro de Aprendizaje de Idiomas (CAI) de la UPV

Equipo de desarrollo:
-Jose Marco Fuentes Escamilla
-Jessica Sanchez Garcia

#Descripcion:
El sistema cuenta con modulos de ALTA-BAJA-MODIFICACION de alumnos, maestros, supervisores y sesiones.
Un unico usuario tiene privilegios de superadmin, en este caso el director del CAI. La principal 
funcionalidad del sistema es llevar un control de acceso de los alumnos a sesiones del CAI. Para esto
se cuenta con una interfaz principal la cual esta ideada para que el ingreso de alumnos sea fluido
y sin contratiempos. Un usuario con privilegio de maestro puede ingresar al sistema y obtener reportes
detallados de sus alumnos agrupados por cada grupo que este tenga asignado.

El sistema se encuentra desarrollado con el modelado Modelo-Vista-Controlador (MVC)
utilizando como tecnologias: PHP, HTML, CSS, JQuery, AJAX, bajo el paradigma orientado a objetos.

**SERVER**
El sistema se encuentra activo y en desarrollo en la siguiente URL:
http://159.89.149.78/cai/

#Llaves de autenticacion:
SUPERADMIN:
user: admin  |  pass: admin

EJEMPLO DE MAESTRO:
user: arturo | pass: arturo


