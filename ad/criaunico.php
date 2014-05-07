<?php
  $forbidden = array("/", ";", "|", "&");
  $usuario = str_replace($forbidden, "", $_POST["usuario"]);
  $senha = str_replace($forbidden, "", $_POST["password"]);
  $lista = "\"".str_replace($forbidden, "", $_POST["login"]).",".str_replace($forbidden, "", $_POST["nome"]).",".str_replace($forbidden, "", $_POST["sobrenome"]).",".
  str_replace($forbidden, "", $_POST["uf"]).",".str_replace($forbidden, "", $_POST["grupos"])."\"";
 
  system("bash /var/www/sis/ad/criaunico.sh $usuario $senha $lista");
?>
