<?php

function showHeader ($title)
{

$logout_link = "";
$register_link = "";
$admin_link = "";

if(isset($_SESSION["id_user"])){
$logout_link = <<<EOD
	<li><a href="logout.php">Logout</a></li>
EOD;
if($_SESSION["id_user"] == 1){
$admin_link = <<<EOD
	<li><a href="admin.php">Admin</a></li>
EOD;
}
}else{
$register_link = <<<EOD
<li><a href="register.php">Registro</a></li>
EOD;
}
echo <<<EOD
<html>

<head>
<link rel="stylesheet" href="estilo.css">
<title>{$title}</title>
</head>

<body>
<header>
<h1>{$title}</h1>
</header>

<nav>
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="tienda.php">Tienda</a></li>
{$register_link}
{$logout_link}
{$admin_link}
</ul>
</nav>
EOD;

}


function showContent ($content){
echo <<<EOD
<main>
{$content}
</main>
EOD;
}


function showFooter() {

echo <<<EOD
<footer>
<p> Todos los derechos reservados (c) 2021 </p>
</footer>
</body>

</html>
EOD;
}
?>
