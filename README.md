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

Como bien dice su nombre, ```Template.php``` es la base de todos nuestros scripts.
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
```Index.php``` es el home de nuestra ENTIenda.

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

```Login_req.php``` es el formulario encargado de recibir y procesar la información que envia index.php al mandar el formulario de inicio de sesión.

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

## Logout.php

### Funcionamiento

```php
<?php

session_start();

$_SESSION["id_user"] = 0;

session_destroy();

header("Location: index.php");

exit();
?>
```

El funcionamiento de logout es bastante sencillo: se inicia una sesión y se setea el id del usuario conectado a 0 (usuario que es imposible que exista ya que los ids empiezan a partir del 1), y a continuación se destruye la sesión y se manda al usuario a la página de login.

## Register.php

### Vista de la página

![image](https://user-images.githubusercontent.com/77392609/146655704-f44d7aa7-f759-4696-85f7-7f73d3dd9c17.png)

### Funcionamiento 

```php
<?php
session_start();

require("config.php");
require("template.php");

$content = <<<EOD
<form method="post" action="register_req.php">
<h2>¡Registrate!</h2>
<p><label for="register-user">Usuario:</label><input type="text" name="user" id="register-user"/></p>
<p><label for="register-pass">Password:</label><input type="password" name="pass" id="register-pass"/></p>
<p><label for="register-confirmpass">Confirm password:</label><input type="password" name="repass" id="register-repass"/></p>
<p><label for="register-name">Name:</label><input type="text" name="name" id="register-name"/></p>
<p><label for="register-surname">Surname:</label><input type=text" name="surname" id="register-surname"/></p>
<p><label for="register-email">Email:</label><input type="email" name="email" id="register-email"/></p>
<p><label for="register-birthday">Birthday:</label><input type="date" name="birthday" id="register-birthday"/></p>
<p><input type="submit" id="register-submit" value="Register"/></p>
</form>
EOD;

showHeader("ENTIenda: Register");
showContent($content);
showFooter();

?>
```

La página de ```register.php```, como su propio nombre indica, contiene un formulario cuyos campos son los necesarios para efectuar correctamente un registro de nuevo usuario en nuestra base de datos. Todos los datos serán enviados posteriormente a ```register_req.php``` para procesarlos.

## Register_req.php

### Funcionamiento

```php
<?php

if(!isset($_POST["user"]) || !isset($_POST["pass"]) || !isset($_POST["repass"]) || !isset($_POST["name"]) || !isset($_POST["surname"]) || !isset($_POST["email"]) || !isset($_POST["birthday"])){
echo "ERROR 1: No se han rellenado todos los campos";

exit();
}

$user = trim($_POST["user"]);
if(strlen($user) <= 2){
echo "ERROR 2: Usuario mal formado";
exit();
}

$pass = trim($_POST["pass"]);
if(strlen($pass) <= 3){
echo "ERROR 3: Contraseña mal formada";
exit();
}

$repass = trim($_POST["repass"]);

if($pass != $repass){
echo "ERROR 4: Las contraseñas no coinciden";
exit();
}

$name = trim($_POST["name"]);

$surname = trim($_POST["surname"]);

$email = trim($_POST["email"]);

$birthday = trim($_POST["birthday"]);

$tmp = addslashes($user);

if(strlen($tmp) != strlen($user)){
echo "ERROR 5: Que no me intentes hackear con el username, tonto";
exit();
}

$user = $tmp;
$tmp = addslashes($pass);

if(strlen($tmp) != strlen($pass)){
echo "ERROR 6: Que no me intentes hackear con la pass, tonto";
exit();
}

$pass = md5($tmp);
$tmp = addslashes($name);

if(strlen($tmp) != strlen($name)){
echo "ERROR 7: Que no me intentes hackear con el name, tonto";
exit();
}

$name = $tmp;
$tmp = addslashes($surname);

if(strlen($tmp) != strlen($surname)){
echo "ERROR 8: Que no me intentes hackear con el surname, tonto";
exit();
}

$surname = $tmp;

$query = <<<EOD
INSERT INTO users (user, password, email, name, surname, birthdate)
VALUES ('{$user}','{$pass}','{$email}','{$name}','{$surname}','{$birthday}');
EOD;

require("config.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

if(!$conn){
echo "ERROR 9: Mala conexión con la entienda";
exit();
}

$res = $conn->query($query);

if(!$res){
echo "ERROR 10: Query mal formada";
exit();
}

$id_user = mysqli_insert_id($conn);

session_start();

$_SESSION["id_user"] = $id_user;

header("Location: index.php");
exit();

?>
```

El funcionamiento de register_req.php es muy parecido al de ```login_req.php```, ya que comprobamos que todos los campos han sido cumplimentados correctamente gracias a las funciones de isset, trim y addslashes y además añadimos algunas comprobaciones extra como por ejemplo que la contraseña y la confirmación de contraseña sean iguales.

La principal diferencia entre ambos scripts, es que este en lugar de buscar al usuario en la tabla de users, lo inserta, generando así un nuevo usuario que posteriormente podrá logearse en nuestra página web con el usuario y contraseña que haya especificado en su registro.

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

## Groups.php

### Vista de la página

![image](https://user-images.githubusercontent.com/77392609/146655833-f849a8eb-4dad-46ab-9ce5-4321a04aea16.png)

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


$content = "";
$content = <<<EOD
<form method="post" action="groups_req.php" id="group_form">
<h2>Inserción de nuevo grupo</h2>
<p><label for="group">Group</label><input type="text" name="group" id="group" /></p>
<p><label for="course">Course</label><input type="text" name="course" id="course" /></p>
<p><label for="jamyear">Jam Year</label><input type="text" name="jamyear" id="jamyear" /></p>
<p><label for="mark">Mark</label><input type="text" name="mark" id="mark" /></p>
<p><input type="submit" value="Create group"/></p>
EOD;

showHeader("ENTIenda ADMIN - GROUPS");
showContent($content);
showFooter();
?>
```

La página de groups.php es una de las 2 que es única y exclusivamente accesible si eres root.

Esta contiene un formulario que permite al administrador de la página web inscribir a un nuevo grupo de creadores de juegos en nuestra web. La información será procesada por ```groups_req.php```

## Groups_req.php

### Funcionamiento

```php
<?php


if(!isset($_POST["group"]) || !isset($_POST["course"]) || !isset($_POST["jamyear"]) || !isset($_POST["mark"])){
echo "ERROR 1: No se han rellenado todos los campos";

exit();
}

$group = trim($_POST["group"]);
$course = trim($_POST["course"]);
$jamyear = trim($_POST["jamyear"]);
$mark = trim($_POST["mark"]);

$query = <<<EOD
INSERT INTO groups (`group`, course, jam_year, mark)
VALUES ('{$group}',{$course},'{$jamyear}',{$mark});
EOD;

require("config.php");

$conn = mysqli_connect($db_server,$db_user,$db_pass, $db);

if(!$conn){
echo "ERROR: No se ha podido conectar a la base de datos";
exit();
}

$res = $conn->query($query);
echo $query;
if(!$res){
echo "ERROR: Query mal formada";
exit();
}

Header("Location: admin.php");
?>
```

En general, todos los scripts que acaban en ```_req.php``` tienen un funcionamiento muy similar entre ellos, solo que el procesamiento de la información de cara a la base de datos varia en la tabla en la que se trabaja y si es inserción o seleccion de datos.

En este caso, lo que estamos haciendo es recoger la información del formulario en ```groups.php``` mediante POST, procesarla y a continuación insertarla en nuestra tabla de groups para que después se pueda utilizar en nuestra siguiente página: ```product.php```.

## Product.php

### Vista de la página

· Especificando producto

![image](https://user-images.githubusercontent.com/77392609/146655995-b786d595-0d5e-4ec4-ad3e-581e22c4d65b.png)

· Sin especificar

![image](https://user-images.githubusercontent.com/77392609/146655999-8421f949-b815-41e6-bd77-7d8296c15f4d.png)

### Funcionamiento
```php
<?php

require("template.php");

$content = "";

$id_product = 0;

require("config.php");


if(isset($_GET["id_product"])){
$id_product = intval($_GET["id_product"]);
}

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

$query = <<<EOD
SELECT * FROM groups;
EOD;

$res = $conn->query($query);

$groups = "";
$engines = "";

while($prod = $res->fetch_assoc()){

$groups .= <<<EOD
	<option value="{$prod["id_group"]}">{$prod["group"]}</option>
EOD;
}

$query = <<<EOD
SELECT * FROM engines;
EOD;

$res = $conn->query($query);

while($prod = $res->fetch_assoc()){
$engines .= <<<EOD
<option value="{$prod["id_engine"]}">{$prod["engine"]}</option>
EOD;
}
if($id_product == 0){
$content = <<<EOD
<form method="post" action="product_insert.php" id="product-form">
<h2>Inserción de nuevo producto</h2>
<p><label for="product">Product</label><input type="text" name="product" id="product" /></p>
<p><label for="description">Description</label><input type="text" name="description" id="description" /></p>
<p><label for="price">Price</label><input type="text" name="price" id="price" /></p>
<p><label for="reference">Reference</label><input type="text" name="reference" id="reference" /></p>
<p><label for="website">Website</label><input type="text" name="website" id="website" /></p>
<p>Groups</p>
<select name="id_group">{$groups}</select>
<p>Engines</p>
<select name="id_engine_version">{$engines}</select>
<p><input type="submit" /></p>
</form>
EOD;
}else{	
	require("config.php");
	$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

	$query = <<<EOD
	SELECT * FROM products WHERE id_product={$id_product};
EOD;

$res = $conn->query($query);

if(!$res){
echo "Mala query";
exit();
}

if($res->num_rows !=1){
echo "Error, producto erroneo";
exit();
}

$prod = $res->fetch_assoc();

$content = <<<EOD
<form method="post" action="product_update_req.php" id="product-form">
<input type="hidden" name="id_product" value="{$prod["id_product"]}" />
<h2>Actualización de producto</h2>
<p><label for="product">Product</label><input type="text" name="product" id="product" value="{$prod["product"]}"/></p>
<p><label for="description">Description</label><input type="text" name="description" id="description" value="{$prod["description"]}"/></p>
<p><label for="price">Price</label><input type="text" name="price" id="price" value="{$prod["price"]}"/></p>
<p><label for="reference">Reference</label><input type="text" name="reference" id="reference" value="{$prod["reference"]}" /></p>
<p><label for="website">Website</label><input type="text" name="website" id="website" value="{$prod["website"]}"/></p>
<p><label for="id_group">ID Group</label><input type="text" name="id_group" id="id_group" value="{$prod["id_group"]}"/></p>
<p><label for="id_engine_version">ID Engine Version</label><input type="text" name="id_engine_version" id="id_engine_version" /value="{$prod["id_engine_version"]}"></p>
<p><input type="submit" /></p>
</form>
EOD;

}
showHeader("ENTIenda ADMIN");
showContent($content);
showFooter();

?>
```

Esta página si que contiene algo más de miga que las anteriores ya que se le puede dar 2 usos.
- **Indicando un producto en su url añadiendo ```?id_product=x``` al final:**
Si se indica un número de producto en la url, en lugar de insertar un producto lo que se estará haciendo es EDITAR el producto que se haya escogido y al enviarse, se procesará en ```product_update_req.php```.
- **Sin indicar un producto en su url:**
Si no se indica un número de producto, el formulario se mandará a ```product_insert.php``` y se añadirá a la lista de productos.

Un punto interesante de este script, son los selectores de groups y de engines que se encuentran en los formularios, ya que gracias a un while se recorren las tablas necesarias para poder otorgar a los ```<select></select>``` las ```<option></option>``` necesarias para que estos muestren la información correcta.

## Product_update_req.php

### Funcionamiento

```php
<?php

session_start();

if(!isset($_SESSION["id_user"])){
echo "Inicia sesion";
exit();
}

if($_SESSION["id_user"] != 1){
echo "Usuario incorrecto";
exit();
}

if(!isset($_POST["id_product"]) || !isset($_POST["product"]) || !isset($_POST["description"]) || !isset($_POST["price"]) || !isset($_POST["reference"]) || !isset($_POST["website"]) || !isset($_POST["id_group"]) || !isset($_POST["id_engine_version"])){
echo "ERROR 1: Formulario mal rellenado";
exit();
}

$id_product = intval(trim($_POST["id_product"]));

$product = trim($_POST["product"]);

$description = trim($_POST["description"]);

$price = trim($_POST["price"]);

$reference = trim($_POST["reference"]);

$website = trim($_POST["website"]);

$id_group = trim($_POST["id_group"]);

$id_engine_version = trim($_POST["id_engine_version"]);
$query = <<<EOD
UPDATE products 
SET product='{$product}', description='{$description}',price={$price},reference='{$reference}',website='{$website}',id_group={$id_group},id_engine_version={$id_engine_version}
WHERE id_product={$id_product};
EOD;

require("config.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

if(!$conn){
echo "ERROR 2: No se ha podido conectar con la base de datos.";
exit();
}

$res = $conn->query($query);

if(!$res){
echo "ERROR 3: Query mal formada";
exit();
}

header("Location: tienda.php?id_product=".$id_product);
exit();
?>
```

Como he comentado anteriormente, todos los archivos ```_req.php``` mantienen la misma estructura, y es que este funciona exactamente igual que el resto solo que la query lo que hace es actualizar el producto que estemos utilizando con los nuevos datos que le mandemos.

## Product_insert.php

### Funcionamiento
```php
<?php

session_start();

if(!isset($_SESSION["id_user"])){
echo "Inicia sesion";
exit();
}

if($_SESSION["id_user"] != 1){
echo "Usuario incorrecto";
exit();
}

if(!isset($_POST["product"]) || !isset($_POST["description"]) || !isset($_POST["price"]) || !isset($_POST["reference"]) || !isset($_POST["website"]) || !isset($_POST["id_group"]) || !isset($_POST["id_engine_version"])){
echo "ERROR 1: Formulario mal rellenado";
exit();
}

$product = trim($_POST["product"]);

$description = trim($_POST["description"]);

$price = trim($_POST["price"]);

$reference = trim($_POST["reference"]);

$website = trim($_POST["website"]);

$id_group = trim($_POST["id_group"]);

$id_engine_version = trim($_POST["id_engine_version"]);
$query = <<<EOD
INSERT INTO products (product, description, price, reference, discount, units_sold, website, size, duration, release_date, id_group, id_engine_version)
VALUES ('{$product}','{$description}',{$price},'{$reference}',0,0,'{$website}',0,0,'0000-00-00',{$id_group},{$id_engine_version});
EOD;
echo $query;
require("config.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

if(!$conn){
echo "ERROR 2: No se ha podido conectar con la base de datos.";
exit();
}

$res = $conn->query($query);

if(!$res){
echo "ERROR 3: Query mal formada";
exit();
}

$id_product = mysqli_insert_id($conn);

header("Location: tienda.php?id_product=".$id_product);
exit();
?>
```

```Product_insert.php``` en cambio, en lugar de actualizar el producto lo que hace es añadirlo a la tabla de products, habilitando así su uso y/o compra en ```tienda.php```.

## Tienda.php

### Vista de la página

![image](https://user-images.githubusercontent.com/77392609/146656370-37edd83c-5956-46f0-9bd2-6c44ac5936c2.png)

### Funcionamiento

```php
<?php
session_start();

require("config.php");
require ("template.php");

$conn = mysqli_connect ($db_server, $db_user, $db_pass, $db);
if(!$conn){
echo "No se ha podido conectar con la base de datos";
}

if (isset($_GET["id_product"])){
	$id_product = intval($_GET["id_product"]);

$query = <<<EOD
	SELECT * FROM products WHERE id_product={$id_product};
EOD;
}
else{
$query = <<<EOD
	SELECT * FROM products;
EOD;
}
$res = $conn->query($query);

$content="";

if($res->num_rows >1){

	while ($prod = $res->fetch_assoc()){
	 $content .= <<<EOD
<section>	
	<h2>{$prod["product"]}</h2>
	<p><a href="tienda.php?id_product={$prod["id_product"]}">Ver</a></p>
</section>	
EOD;
	
	}

}
else if($res->num_rows == 1){

$prod = $res->fetch_assoc();

$admin_link = "";
$buy_link = "";
if(isset($_SESSION["id_user"])){
	if($_SESSION["id_user"] == 1){
	$admin_link = <<<EOD
<p>[<a href="admin.php?id_product={$prod["id_product"]}">EDITAR</a>]</p>
EOD;
	}
	else{
	$query = <<<EOD
	SELECT * FROM users_products WHERE id_user={$_SESSION["id_user"]} AND id_product={$prod["id_product"]};
EOD;
	
	$res = $conn->query($query);
	if($res){
		if($res->num_rows == 0){
			$buy_link = <<<EOD
			<form method="post" action="buy_req.php">
			<input type="hidden" name="id_product" value="{$prod["id_product"]}" />
			<p><input type="submit" value="Comprar" /></p>
			</form>
EOD;
		}else{
			$buy_link = "<p>COMPRADO!!</p>";
		}
	}
	}
}

$content = <<<EOD
	{$admin_link}
	{$buy_link}
	<h2>Nombre del producto: {$prod["product"]}</h2>
	<p><strong>Descripcion:</strong> {$prod["description"]}</p>
	<p><strong>Precio:</strong> {$prod["price"]}</p>
	<p><strong>Referencia:</strong> {$prod["reference"]}</p>
	<p><strong>Descuento:</strong> {$prod["discount"]}</p>
	<p><strong>Unidades vendidas:</strong> {$prod["units_sold"]}</p>
	<p><strong>Pagina Web:</strong> {$prod["website"]}</p>
	<p><strong>Tamaño:</strong> {$prod["size"]}</p>
	<p><strong>Duración:</strong> {$prod["duration"]}</p>
	<p><strong>Fecha de salida:</strong> {$prod["release_date"]}</p>
	<p><strong>Grupo:</strong> {$prod["id_group"]}</p>
	<p><strong>Versión del engine:</strong> {$prod["id_engine_version"]}</p>
EOD;
}else{
echo "No hay productos con esa referencia";
}
	showHeader("ENTIenda: Tienda");
	showContent($content);
	showFooter();
?>
```
La página de ```tienda.php``` es básicamente el core de nuestra web. En ella, el usuario, en caso de ser admin, puede editar los juegos o en caso de no serlo, puede comprarlos.

Este script funciona gracias a una serie de condiciones en las que determinamos qué es lo que se va a mostrar por pantalla:
- En caso de recibir un numero de producto por GET (es decir, por la URL), la página mostrará ese producto.
- En caso de no recibir ningun producto concreto, la página mostrará todos los productos disponibles en nuestra tienda.
- Si el usuario hace click en algun producto, la página que se mostrará será la misma que si se recibe un producto por GET, pero hay una pequeña diferencia entre ser admin y no serlo. En caso de serlo, arriba aparecerá un botón de editar producto:

![image](https://user-images.githubusercontent.com/77392609/146656485-15d5d71d-49ab-40a5-8dfd-d82c7c6b5200.png)

En caso de no serlo, aparecerá un botón de comprar producto, que al ser pulsado se activará el script de ```buy_req.php```.

![image](https://user-images.githubusercontent.com/77392609/146656509-c0427eae-a7c4-4fe9-b23c-1b6c0e7238c1.png)

## Buy_req.php

### Funcionamiento

```php
<?php

if(!isset($_POST["id_product"])){
echo "Error: no hay producto";
exit();
}

session_start();

if(!isset($_SESSION["id_user"])){
echo "Error: no hay usuario";
exit();
}

$id_product = intval($_POST["id_product"]);

if($id_product == 0){
echo "Error: producto erróneo";
exit();
}

require("config.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

$query = <<<EOD
INSERT INTO users_products (id_user, id_product)
VALUES ({$_SESSION["id_user"]},{$id_product});
EOD;

$res = $conn->query($query);

if (!$res){
echo "Error al insertar producto";
exit();
}

header("Location: tienda.php?id_product=".$id_product);

?>
```
```buy_req.php``` seria nuestro equivalente a una cesta de la compra. Este script es el encargado de añadir a la tabla de productos obtenidos por el user el producto en el que el usuario haya dado click al botón de comprar. Una vez comprado, la pantalla que le saldrá al usuario será esta:

![image](https://user-images.githubusercontent.com/77392609/146656569-bb2f5dec-b066-4a0e-a253-fe7f862a302b.png)
