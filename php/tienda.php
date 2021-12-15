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
