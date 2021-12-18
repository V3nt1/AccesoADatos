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
