<?php
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
  move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
  system("bash /var/www/sis/chamados/contachamados upload/" . $_FILES["file"]["name"]);
  system("bash /var/www/sis/sla/sla upload/" . $_FILES["file"]["name"]);
  }
?>
<script type="text/javascript"> window.location.href = 'chart.html'; </script>

