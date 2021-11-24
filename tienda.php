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

$content = <<<EOD
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
