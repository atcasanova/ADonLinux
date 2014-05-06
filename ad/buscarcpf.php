<?php
include 'selad.php';
$AD_Auth_User = $argv[1];
$AD_Auth_PWD = $argv[2];
$LDAPFieldsToFind = array("cn", "givenname", "samaccountname","streetaddress", "distinguishedname");
$SearchFor=$argv[3];
$SearchField="samaccountname";
$filter="($SearchField=$SearchFor)";
$OUDesativados="OU=Usuarios,OU=Desativados,OU=DNIT,".$dc;

$ds = ldap_connect($AD_server);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
$ver_log = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD);

$sr = ldap_search($ds, $dc, $filter, $LDAPFieldsToFind);
$info = ldap_get_entries($ds, $sr);

for ($x=0; $x<$info["count"]; $x++) {
    $sam=$info[$x]['samaccountname'][0];
    $giv=$info[$x]['givenname'][0];
    $nam=$info[$x]['cn'][0];
    $dn=$info[$x]['distinguishedname'][0];
	$cpf=$info[$x]['streetaddress'][0];
  }
  
  if (!$ver_log){
  	print "<p style='color:#D36F17;'>Usuário ou senha do autenticador está incorreta(s).</p>";
  }elseif ($x==0) {
	print "<p style='color:#C93434;'>Erro, login <b>$SearchFor</b> não foi encontrado. Por favor tente de novo.</p>"; 
  }else{
	$cpf = substr($cpf , 4);
	echo $cpf ."\n";
	echo $dn ."\n";
	echo $nam ."\n";
	return $cpf;
	}
?>