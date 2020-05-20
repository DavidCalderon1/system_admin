
## Requerimientos
Este proyecto esta hecho el laravel 7 y fue desarrollado en linux ubuntu 18.04

- PHP >= 7.2.5 (sudo apt-get install php7.2)
- Composer https://getcomposer.org/
- Mysql 5.7 
- node
- npm

**Instación:**
* Instalar los requerimientos 
(Dependiendo del sistema puede que en algun momento pida extensiones de php)
* Crear una base de datos mysql con el nombre faro_system_admin
* Clonar el repositorio
* Una vez clonado el repositorio se debe copiar el fichero .env.example y
crear uno nuevo con el nombre .env, dentro de el debe modificar 
las variables de conexion de la base de datos por ejemplo:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=faro_system _admin

DB_USERNAME=USUARIO_MYSQL

DB_PASSWORD=PASSWORD_MYSQL

Una vez configurada la base de datos y las variables de entorno
se deben ejecutar los siguientes comandos ubicado en la raíz del proyecto:

* **composer install**
* **php artisan migrate**
* **php artisan bd:seed**
* **npm install**
* **npm run dev**

**Nota** en caso de que npm genere error por algun paquete faltante ejecutar:
* **npm install vue bootstrap-vue bootstrap** 
* **npm install vue-alertify** 
* **npm install v-select2-component –save**  

Para ejecutar el serve del proyecto para iniciar la aplicacion ejecutar:
* **php artisan serve**

Como resultado le retornará la url (http://localhost:puerto) con el puerto que utilizó el proyecto para ser ejecutado.

La URL lo enviará al login, se debe ingresar con las credenciales:
 * Usuario: **admin@admin.com**
 * Password: **admin**
 
**Nota:** Los modulos dessarrollados fueron:
* Terceros (Terminado)
* Inventario-> Productos (Terinado)
* Inventario-> Categorias de productos (Terminado)
* Inventario-> Bodegas (Terminado)
* Transacciones-> Ventas (En pruebas para mejoras)
* Transacciones-> Compras (En pruebas para mejoras)
* Configuracion-> usuarios (Terminado)
* Configuracion-> Roles y permisos (Terminado)
* Configuracion->Transacciones-> Centros de costo (Terminado)
* Configuracion->Transacciones-> Concepto de gasto (Terminado)
* Configuracion->Transacciones-> Impuestos (Terminado)
