<?php
  $forbidden = array("/", ";", "|", "&", "*", "!");
  $usuario = str_replace($forbidden, "", $_POST["usuario"]);
  $senha = $_POST["password"];
  system("bash /var/www/sis/ad/buscaQuantia.sh $usuario $senha");
?>
