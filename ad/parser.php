<?php
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
  $forbidden = array("/", ";", "|", "&");
  $usuario = str_replace($forbidden, "", $_POST["usuario"]);
  $senha = str_replace($forbidden, "", $_POST["password"]);
  move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
  system("bash /var/www/sis/ad/criador $usuario $senha upload/" . $_FILES["file"]["name"]);
  }
?>

