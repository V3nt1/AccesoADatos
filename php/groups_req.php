<?php


if(!isset($_POST["group"]) || !isset($_POST["course"]) || !isset($_POST["jamyear"]) || !isset($_POST["mark"])){
echo "ERROR 1: No se han rellenado todos los campos";

exit();
}

$group = trim($_POST["group"]);
$course = trim($_POST["course"]);
$jamyear = trim($_POST["jamyear"]);
$mark = trim($_POST["mark"]);

$query = <<<EOD

INSERT INTO groups (`group`, course, jam_year, mark)
VALUES ('{$group}',{$course},'{$jamyear}',{$mark});

EOD;

require("config.php");

$conn = mysqli_connect($db_server,$db_user,$db_pass, $db);

if(!$conn){
echo "ERROR: No se ha podido conectar a la base de datos";
exit();
}

$res = $conn->query($query);
echo $query;
if(!$res){
echo "ERROR: Query mal formada";
exit();
}

Header("Location: admin.php");
?>
