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
