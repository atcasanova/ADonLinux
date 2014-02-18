<?php

//$forbidden = array("/", ";", "|", "&");

$logins = str_replace($forbidden, "", $argv[4]);
$grupo = str_replace($forbidden, "", $argv[3]);
$usuario = str_replace($forbidden, "", $argv[1]);
$senha = str_replace($forbidden, "", $argv[2]);

  system("bash /var/www/sis/ad/criagrupo $usuario $senha $grupo '$logins'");

?>

