<?php
include 'selad.php';

$forbidden = array("/", ";", "|", "&");

$logins = str_replace($forbidden, "", $_POST["logins"]);
$grupo = str_replace($forbidden, "", $_POST["grupo"]);
$usuario = str_replace($forbidden, "", $_POST["usuario"]);
$senha = str_replace($forbidden, "", $_POST["senha"]);

if (isset($_POST['docsscaneados'])) {
	$docs=1;
} else {
	$docs=0;
}

  system("bash /var/www/sis/ad/criagrupo $usuario $senha $grupo $docs '$logins'");

if (isset($_POST['docsscaneados'])) {
        //mover $grupo para a OU dos grupos do docsscaneados
		$newOU = 'OU=Grupos do DocsScanner,OU=Grupos,OU=DF,DC=intranet';
} else {
        //mover $grupo para a OU dos grupos.
		$newOU = 'OU=Grupos de Acesso a arquivos,OU=Grupos,OU=DF,DC=intranet';
}

$AD_Auth_User = $usuario;
$AD_Auth_PWD = $senha;
$dn = "DC=intranet";
$LDAPFieldsToFind = array("cn", "samaccountname", "distinguishedname");
$LDAPUserDomain = "@intranet";
$SearchFor=$grupo;
$SearchField="samaccountname";
$filter="($SearchField=$SearchFor)";

$ds = ldap_connect($AD_server);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
$ver_log = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD);

$sr = ldap_search($ds, $dn, $filter, $LDAPFieldsToFind);

$info = ldap_get_entries($ds, $sr);

 for ($x=0; $x<$info["count"]; $x++) {
    $sam=$info[$x]['samaccountname'][0];
    $nam=$info[$x]['cn'][0];
    $ou=$info[$x]['distinguishedname'][0];
  }
  if (!$ver_log){
  	print "<p style='color:#D36F17;'>Usuário ou senha do autenticador está incorreta(s).</p>";
  }elseif ($x==0) {
	print "<p style='color:#C93434;'>Erro, grupo <b>$SearchFor</b> não foi encontrado. Por favor tente de novo.</p>"; 
  }else{
        $dn=$ou;

        //system("net ads password " . $SearchFor . "@INTRANET Nova" . $ano . " -U" .$AD_Auth_User. "%" . $AD_Auth_PWD . " 2&> /dev/null");

        if ($ds) {
           ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
           ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
           $r = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD); //BIND
           
           $mo = ldap_rename($ds, $dn, 'cn=' . $nam, $newOU, true);
           
           if ($mo){
           	print "<p style='color:#2CA823;'>Grupo <b>$nam</b> movido para ".$newOU." com sucesso.</p>";
            
	       }else{
	       	print "<p style='color:#C93434;'>Erro, grupo <b>$nam NÃO</b> movido com sucesso.</p>";
		   }
        } else {
        	echo "<p style='color:#D36F17;'><b>Erro</b>, não foi possivel conectar no servidor LDAP: $AD_server.</p>";
        }

       }

?>


