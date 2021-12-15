<?php

session_start();

$_SESSION["id_user"] = 0;

session_destroy();

header("Location: index.php");

exit();
?>
