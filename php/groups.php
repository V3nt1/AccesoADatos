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


$content = "";
$content = <<<EOD
<form method="post" action="groups_req.php" id="group_form">
<h2>Inserción de nuevo grupo</h2>
<p><label for="group">Group</label><input type="text" name="group" id="group" /></p>
<p><label for="course">Course</label><input type="text" name="course" id="course" /></p>
<p><label for="jamyear">Jam Year</label><input type="text" name="jamyear" id="jamyear" /></p>
<p><label for="mark">Mark</label><input type="text" name="mark" id="mark" /></p>
<p><input type="submit" value="Create group"/></p>
EOD;

showHeader("ENTIenda ADMIN - GROUPS");
showContent($content);
showFooter();
?>
