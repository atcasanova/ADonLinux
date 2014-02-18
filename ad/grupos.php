<?php

$forbidden = array("/", ";", "|", "&");

$logins = str_replace($forbidden, "", $_POST["logins"]);
$grupos = str_replace($forbidden, "", $_POST["grupos"]);
$usuario = str_replace($forbidden, "", $_POST["usuario"]);
$senha = str_replace($forbidden, "", $_POST["senha"]);

  system("bash /var/www/sis/ad/grupos -L $usuario -S $senha -G '$grupos' -U '$logins'");

?>

