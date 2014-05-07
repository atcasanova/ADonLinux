<?php
  $forbidden = array("/", ";", "|", "&", "*", "!");
  $usuario = str_replace($forbidden, "", $_POST["usuario"]);
  $senha = $_POST["password"];
  $logins = str_replace($forbidden, "", $_POST["lista"]);
  system("bash /var/www/sis/ad/busca.sh $usuario $senha $logins");
?>
