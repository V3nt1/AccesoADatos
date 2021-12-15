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
