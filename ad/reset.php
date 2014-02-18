<?php
include 'selad.php';
$AD_Auth_User = $argv[1];
$AD_Auth_PWD = $argv[2];
$dn = "DC=intranet";
$LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "distinguishedname");
$LDAPUserDomain = "@intranet";
$SearchFor=$argv[3];
$SearchField="samaccountname";
$filter="($SearchField=$SearchFor)";
$ano=$argv[4];
$OUDesativados="OU=Usuarios,OU=Desativados,OU=DNIT,DC=intranet";

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
  }
  if(!$ver_log){
  	print "<p style='color:#D36F17;'>Usuário ou senha do autenticador está(ão) incorreta(s).</p>";
  }elseif ($x==0) {
  	print "<p style='color:#C93434;'>Erro, login <b>$SearchFor</b> não foi encontrado. Por favor tente de novo.</p>\n";
  }elseif(substr($ou , strlen($nam)+4)==$OUDesativados){
	print "<p style='color:#C93434;'>Erro, login <b>$SearchFor</b> está DESATIVADO. Por favor entre em contato com a CGMI.</p>";
  }else{
	if ($ds) {
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
		ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
		$r = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD); //BIND

		$ldaprecord2['UserAccountControl'] = "544";
		$ldaprecord2['pwdLastSet'] = "0";

		$r = ldap_mod_replace($ds, $ou, $ldaprecord2);
	
		print "<p style='color:#2CA823;'>O usuario <b>$SearchFor</b> necessita  alterar a senha no proximo login. </p>";
	} else {
		echo "<p style='color:#D36F17;'><b>Erro</b>, não foi possivel conectar no servidor LDAP: $AD_server.</p>";
	}
  }



//system("net ads password " . $SearchFor . "@INTRANET Nova" . $ano . " -U" .$AD_Auth_User. "%" . $AD_Auth_PWD . " 2&> /dev/null");



?>
