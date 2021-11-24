<?php
session_start();

if(!isset($_SESSION["id_user"])){
	echo "Es obligatorio identificarse!";
	exit();
}

if(intval($_SESSION["id_user"]) != 1){
	echo "No tienes permiso para estar aqui!";
	exit();
}



require("template.php");
require("config.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db);

$content = "";

$content = <<<EOD

<form method="post" action="product_insert.php">
<h2>Inserción de nuevo producto</h2>
<p><label for="product">Product</label><input type="text" name="product" id="product" /></p>
<p><label for="description">Description</label><input type="text" name="description" id="description" /></p>
<p><label for="price">Price</label><input type="text" name="price" id="price" /></p>
<p><label for="reference">Reference</label><input type="text" name="reference" id="reference" /></p>
<p><label for="website">Website</label><input type="text" name="website" id="website" /></p>
<p><label for="id_group">ID Group</label><input type="text" name="id_group" id="id_group" /></p>
<p><label for="id_engine_version">ID Engine Version</label><input type="text" name="id_engine_version" id="id_engine_version" /></p>
<p><input type="submit" /></p>
</form>

EOD;



showHeader("ENTIenda ADMIN");
showContent($content);
showFooter();

?>
