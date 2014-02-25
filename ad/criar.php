<?php
$login = $argv[1];
$givenName = $argv[2];
$SN = $argv[3];
$UF = $argv[4];

$CN = $givenName.' '.$SN;
include 'selad.php';
$AD_Auth_User = $argv[5]; //Administrative user
$LDAPUserDomain = "@intranet";
$AD_Auth_PWD = $argv[6]; //The password
$ano = $argv[7];
$dn = 'CN='.$CN.',OU=Padrao,OU=Usuarios,OU='.$UF.',DC=intranet';

$ds = ldap_connect($AD_server);

if ($ds) {
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
    ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
    $r = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD); //BIND

    $ldaprecord['cn'] = $CN;
    $ldaprecord['givenName'] = $givenName;
    $ldaprecord['sn'] = $SN;
    $ldaprecord['objectclass'][0] = "top";
    $ldaprecord['objectclass'][1] = "person";
    $ldaprecord['objectclass'][1] = "organizationalPerson";
    $ldaprecord['objectclass'][2] = "user";
    $ldaprecord['sAMAccountName'] = $login;
    $ldaprecord['userPrincipalName'] = $login.$LDAPUserDomain;
    $ldaprecord['displayName'] = $CN;

    $r = ldap_add($ds, $dn, $ldaprecord);

	if($r){
		echo "<p style='color:#2CA823;'>Usuário <b>$login</b> criado com sucesso. <br />";
	}else{
  		echo "<p style='color:#D36F17;'>Usuário ou senha do autenticador está(ão) incorreta(s).</p>";
	}
}else{
    echo "<p style='color:#D36F17;'><b>Erro</b>, não foi possivel conectar no servidor LDAP: $AD_server.</p>";
}

//$oe = system("net ads password " . $login . "@INTRANET Nova" . $ano . " -U" . $argv[5] . "@INTRANET%" . $AD_Auth_PWD . " 2&> /dev/null");

print "</p>";




if ($ds) {
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
    ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
    $r = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD); //BIND

    $ldaprecord2['UserAccountControl'] = "544";
    $ldaprecord2['pwdLastSet'] = "0";

    $r = ldap_mod_replace($ds, $dn, $ldaprecord2);

} else {
    echo "<p style='color:#D36F17;'><b>Erro</b>, não foi possivel conectar no servidor LDAP: $AD_server.</p>";
}

?>
