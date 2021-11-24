<?php

function showHeader ($title)
{

echo <<<EOD
<html>

<head>
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
<li><a href="register.php">Registro</a></li>
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
