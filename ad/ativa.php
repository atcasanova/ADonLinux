<?php
  $forbidden = array("/", ";", "|", "&");
  $usuario = str_replace($forbidden, "", $_POST["usuario"]);
  $senha = str_replace($forbidden, "", $_POST["password"]);
  $uf = str_replace($forbidden, "", $_POST["uf"]);
  $logins = str_replace($forbidden, "", $_POST["lista"]);
  system("bash /var/www/sis/ad/ativar $usuario $senha $uf $logins");
?>