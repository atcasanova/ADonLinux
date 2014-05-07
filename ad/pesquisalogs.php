<?php

$forbidden = array("/", ";", "|", "&");

$name = str_replace($forbidden, "", $_POST["fname"]);

  system("bash /var/www/sis/ad/pesquisa.php " . $name);

?>

