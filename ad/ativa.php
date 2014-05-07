<?php
  $forbidden = array("/", ";", "|", "&");
  $usuario = str_replace($forbidden, "", $_POST["usuario"]);
  $senha = str_replace($forbidden, "", $_POST["password"]);
  $uf = str_replace($forbidden, "", $_POST["uf"]);
  $logins = str_replace($forbidden, "", $_POST["lista"]);

if (isset($_POST['naoreseta'])) {
        $reset=0;
} else {
        $reset=1;
}

  system("bash /var/www/sis/ad/ativar.sh $usuario $senha $uf $reset $logins");
?>
