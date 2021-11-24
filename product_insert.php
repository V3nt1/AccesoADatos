<?php

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

echo "Producto añadido exitosamente!";
?>
