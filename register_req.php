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

$query = <<<EOD
SELECT * FROM users WHERE email='{$email}';
EOD;

$res = $conn->query($query);

if(!$res){
echo "ERROR 10: Query mal formada";
exit();
}

$user = $res->fetch_assoc();

session_start();

$_SESSION["id_user"] = $user["id_user"];

echo "Usuario registrado con éxito";
?>
