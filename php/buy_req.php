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
