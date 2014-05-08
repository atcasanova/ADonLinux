<?php
  $forbidden = array("/", ";", "|", "&");
  $usuario = str_replace($forbidden, "", $_POST["usuario"]);
  $senha = str_replace($forbidden, "", $_POST["password"]);
  $logins = str_replace($forbidden, "", $_POST["lista"]);
  system("bash /var/www/sis/ad/senhas.sh $usuario $senha $logins");
?>

