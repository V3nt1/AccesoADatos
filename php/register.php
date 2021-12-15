<?php
session_start();

require("config.php");
require("template.php");

$content = <<<EOD
<form method="post" action="register_req.php">
<h2>¡Registrate!</h2>

<p><label for="register-user">Usuario:</label><input type="text" name="user" id="register-user"/></p>
<p><label for="register-pass">Password:</label><input type="password" name="pass" id="register-pass"/></p>
<p><label for="register-confirmpass">Confirm password:</label><input type="password" name="repass" id="register-repass"/></p>
<p><label for="register-name">Name:</label><input type="text" name="name" id="register-name"/></p>
<p><label for="register-surname">Surname:</label><input type=text" name="surname" id="register-surname"/></p>
<p><label for="register-email">Email:</label><input type="email" name="email" id="register-email"/></p>
<p><label for="register-birthday">Birthday:</label><input type="date" name="birthday" id="register-birthday"/></p>

<p><input type="submit" id="register-submit" value="Register"/></p>
</form>

EOD;

showHeader("ENTIenda: Register");
showContent($content);
showFooter();

?>
