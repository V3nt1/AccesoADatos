# Documentación - Tienda Final

## Explicación de los scripts

## Template.php

### Funcionamiento

```php
<?php

function showHeader ($title)
{

$logout_link = "";
$register_link = "";
$admin_link = "";

if(isset($_SESSION["id_user"])){
$logout_link = <<<EOD
	<li><a href="logout.php">Logout</a></li>
EOD;
if($_SESSION["id_user"] == 1){
$admin_link = <<<EOD
	<li><a href="admin.php">Admin</a></li>
EOD;
}
}else{
$register_link = <<<EOD
<li><a href="register.php">Registro</a></li>
EOD;
}
echo <<<EOD
<html>
<head>
<link rel="stylesheet" href="estilo.css">
<title>{$title}</title>
</head>
<body>
<header>
<h1>{$title}</h1>
</header>
<nav>
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="tienda.php">Tienda</a></li>
{$register_link}
{$logout_link}
{$admin_link}
</ul>
</nav>
EOD;

}


function showContent ($content){
echo <<<EOD
<main>
{$content}
</main>
EOD;
}


function showFooter() {

echo <<<EOD
<footer>
<p> Todos los derechos reservados (c) 2021 </p>
</footer>
</body>
</html>
EOD;
}
?>
```

Como bien dice su nombre, **Template.php** es la base de todos nuestros scripts.
En él se recogen 3 funciones muy importantes, a las que le pasaremos como parámetro el contenido que queremos que tenga nuestra página en todo momento.
Para utilizarlo, solo se necesita hacer un ```require("template.php")``` y llamar a sus funciones.

## Config.php

### Funcionamiento

```php
<?php
$db_server = "localhost";
$db_user = "enti";
$db_pass = "enti";
$db = "entienda";

?>
```

Este no se puede ver en ningún lugar de la página ya que solo lo utilizamos como 'retenedor de información'. 

Lo llamaremos con ```require("config.php")``` ya que contiene toda la información necesaria para poder realizar una conexión a nuestra base de datos.

## Admin.php

### Vista de la página

![image](https://user-images.githubusercontent.com/77392609/146653847-41358707-2591-460a-a553-4b3e72609087.png)

### Funcionamiento

```php
<?php
require("template.php");

session_start();

if(!isset($_SESSION["id_user"])){
	echo "Es obligatorio identificarse!";
	exit();
}

if(intval($_SESSION["id_user"]) != 1){
	echo "No tienes permiso para estar aqui!";
	exit();
}

$content = <<<EOD
<p><a href="product.php">Insert product</a></p>
<p><a href="groups.php">Insert group</a></p>
EOD;
showHeader("ENTIenda ADMIN");
showContent($content);
showFooter();

?>
```

**Este documento solo es accesible por aquel user cuyo id en la base de datos de users sea el primero, es decir, 1.**

En él, además de algunas comprobaciones como ver si el usuario ha iniciado sesión, y si el usuario que trata de acceder es root, encontramos un pequeño 'Hub' que permite al admin insertar un nuevo producto o crear un nuevo grupo.

## Index.php

### Vista de la página

#### Sin identificarse

![image](https://user-images.githubusercontent.com/77392609/146654103-2b0e99c2-68aa-4afe-a000-d37fd364f9fb.png)

#### Identificado

![image](https://user-images.githubusercontent.com/77392609/146654111-09e2dd7f-33c4-433e-ac90-70e65c62dfb2.png)

### Funcionamiento

```php
<?php
session_start();

require("config.php");
require("template.php");


$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);
if(!isset($_SESSION["id_user"])){
$content = <<<EOD
<form method="post" action="login_req.php">
<h2>¡Identificate!</h2>
<p><label for="login-user">Usuario:</label><input type="text" name="user" id="login-user"/></p>
<p><label for="login-pass">Password:</label><input type="password" name="pass" id="login-pass"/></p>
<p><input type="submit" id="login-submit" value="login"/></p>
</form>
EOD;
}else{
$query = "SELECT * FROM users WHERE id_user=".$_SESSION["id_user"];

$res = $conn->query($query);
$user = $res->fetch_assoc();

$content = "<p>Bienvenido/a </p> ".$user["user"];
}

showHeader("ENTIenda: HOME");
showContent($content);
showFooter();

?>
```
Index.php es el home de nuestra ENTIenda.

En él, el usuario, si es nuevo, deberá logearse para poder utilizar las funciones de nuestra página web; ya que en caso contrario no le permitirá realizar ninguna acción.

El funcionamiento es bastante sencillo:
- Primero comprobamos si el usuario ha iniciado sesión, utilizando ```!isset($_SESSION["id_user"])```.
- En caso de que no se haya iniciado ninguna sesión, se muestra el formulario de login de nuestra página web. En caso contrario, accedemos a nuestra base de datos y en una query le enviamos el id del user que está conectado para que nos devuelva su nombre y así poder poner "Bienvenido/a *nombre de usuario*"

## Login_req.php

### Funcionamiento

```php
<?php

if(!isset($_POST["user"]) || !isset($_POST["pass"])){
echo "ERROR 1: Formulario no enviado";

exit();
}

$user = trim($_POST["user"]);
if(strlen($user) <= 2 ){

echo "ERROR 2: Usuario mal formado";
exit();

}

$pass = trim($_POST["pass"]);
if(strlen($pass) <= 3){

echo "ERROR 3: Contraseña mal formada";
exit();

};

$user_tmp = addslashes($user);

if(strlen($user_tmp) != strlen($user)){
echo "ERROR 4: Que no me intentes hackear con el user, tonto";
exit();
}

$user = $user_tmp;

$pass_tmp = addslashes($pass);
if(strlen($pass_tmp) != strlen($pass)){
echo "ERROR 5: Que no me intentes hackear con la pass, tonto";
exit();
}

$pass = md5($pass_tmp);

$query = <<<EOD
SELECT id_user FROM users WHERE user='{$user}' AND password='{$pass}';
EOD;
require("config.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

if(!$conn){
echo "ERROR 6: Mala conexión con la entienda";
exit();
}

$res = $conn->query($query);

if(!$res){
echo "ERROR 7: Query mal formada";
exit();
}

$num = $res->num_rows;

if($num == 0){
echo "ERROR 8: El usuario se ha equivocado";
exit();
}

if($num > 1){
echo "ERROR 9: El usuario nos quiere tongar";
exit();
}

$user = $res->fetch_assoc();

session_start();

$_SESSION['id_user'] = $user["id_user"];

header("Location: index.php");
exit();
?>
```

Login_req.php es el formulario encargado de recibir y procesar la información que envia index.php al mandar el formulario de inicio de sesión.

Vamos a ir viendo poco a poco las comprobaciones que realiza para asegurarse de que la información recibida no sea maliciosa y/o incorrecta:

- #### Función isset

isset nos permite comprobar si la variable que le ponemos como parámetro está seteada, es decir, tiene información en ella.

Es por esto que al hacer ```if(!isset($_POST["user"]) || !isset($_POST["pass"]))```, estamos comprobando que el usuario haya introducido tanto nombre de usuario como contraseña a la hora de intentar iniciar sesion.

- #### Función trim

La función trim nos permite eliminar todos los espacios que tenga la información recibida tanto antes de su primer caracter como despues del último. No elimina en cambio los espacios que se encuentren en medio de la información.

Ejemplo:

```"     hola      "```  quedaria: ```hola```

```"     ho la      "```  quedaria: ```ho la```

- #### Función addslashes

Esta es probablemente la función más importante para evitar que algún usuario intente acceder a información de nuestra base de datos.

Addslashes añade un \ delante de cada caracter que considere que podria llegar a ser malicioso.

Ejemplo:

```php
$str = "Is your name O'Reilly?";

// Outputs: Is your name O\'Reilly?
```
- #### Otras comprobaciones manuales

Además de todo esto, manualmente también podemos hacer ciertas comprobaciones como que el nombre del usuario y la contraseña tengan más de X caracteres.

Tras haber realizado todas estas comprobaciones y asegurarnos de que la información recibida es correcta, haremos un md5 a la contraseña del usuario para cifrarla y posteriormente haremos la conexión pertinente a la base de datos comprobar que el usuario existe en nuestra tabla de users. En caso de que exista, iniciaremos su sesión y en caso de que no, pondremos el error pertinente para informarle de que la información dada no es correcta.





