# backoffice-odourcollect

Establecimiento de un sistema de categorización de usuarios: es necesario asignar categorías de usuario para dar diferentes accesos al back office, en cuanto a los datos accesibles. Se definirán 4 categorías de usuario y un sistema de permisos para cada usuario. Además, la app asignará automáticamente los usuarios de zona a cada zona de estudio definida en función de la ubicación de las observaciones.<br><br>
<b>Administrador general:</b> usuario único con acceso a todos los datos, zonas y usuarios registrados.  
<b>(NUEVO) Administradores de zona:</b> usuario administrador de cada zona de estudio que se defina, correspondiente a una comunidad afectada por contaminación por olores. Este usuario tendrá acceso completo a los datos y estadísticas de su zona de estudio, así como a los usuarios (científicos y científicos ciudadanos) asociados a su zona. Este rol podrá gestionar la información asociada a sus casos de estudio y enviar notificaciones al grupo de usuarios que pertenezcan al mismo.  
<b>Usuarios de zona:</b> científicos y científicos ciudadanos contribuyendo a la recolección de datos en las zonas de estudio definidas (comunidades afectadas por contaminación por olores).  
<b>Community Champions de cada zona:</b> usuarios proactivos en cada zona de estudio (que se definirán, por ejemplo, una vez contribuyan con más de 3 observaciones de olores) que recibirán notificaciones de la app si dan su permiso.

<b>TAREA 1:</b>
- Que el administador pueda asignar a un usuario el Rol de Administrador de Zona que le tendrá que asignar una zona la cual gestionará.
- Incluir el nuevo rol de Administrador de zona: Dicho administrador debe poder gestionar su zona, consultar los usuarios que participan en la misma y las observaciones, así como poder consultar las estadísticas de la zona. Estas funcinoalidades ya están implementadas para el Administrador general, así que habría que hacer lo mismo pero a un nivel inferior.
