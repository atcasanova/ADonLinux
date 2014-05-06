<?php
include 'selad.php';
$AD_Auth_User = $argv[1];
$AD_Auth_PWD = $argv[2];
$dn = "DC=intranet";
$LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "distinguishedname","streetaddress");
$LDAPUserDomain = "@intranet";

$login = $argv[3];

//Recebendo varios argumentos para search
if ($argc<4){
	print "<p style='color:#D36F17'>Preencha no minimo 4 caracteres.</p>";
}else{
  $SearchFor="CPF:";
	for($i=3; $i < $argc; $i++){
	    if(($i+1)==$argc){
	    	$SearchFor=$SearchFor.$argv[$i];
		}else{
			$SearchFor=$SearchFor.$argv[$i]." ";
		}
	}

	$filter="(streetAddress=$SearchFor)";

	$ds = ldap_connect($AD_server);
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
	ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
	$ver_log = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD);

	$sr = ldap_search($ds, $dn, $filter, $LDAPFieldsToFind);

	$info = ldap_get_entries($ds, $sr);

	for ($x=0; $x<$info["count"]; $x++) {
	    $sam=$info[$x]['samaccountname'][0];
	    $giv=$info[$x]['givenname'][0];
	    $nam=$info[$x]['cn'][0];
	    $ou=$info[$x]['distinguishedname'][0];
            $cpf=substr($info[$x]['streetaddress'][0],4);
	      print "<br>Informa√ß√µes do Usu√°rio (<b>".($x+1)."</b>):\n<br>";
	      print "Nome Completo: <b>$nam</b> \n<br>";
	      print "Login: <b>$sam</b> \n<br>";
	      print "DN: <b>$ou</b> \n<br>";
       
              print "CPF: <b>$cpf</b> \n<br>";
	}
	if(!$ver_log){
	  	print "<p style='color:#D36F17;'>Usu·rio ou senha do autenticador est· incorreta(s).</p>";
	}elseif ($x==0) {
	  	print "<p style='color:#C93434;'>Erro, login <b>$SearchFor</b> n„o foi encontrado. Por favor tente de novo.</p>\n"; 
	}
}
?>
