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
