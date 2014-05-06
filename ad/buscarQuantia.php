<?php
//Buscar quantia de usuários e estações por OU.
include 'selad.php';
$AD_Auth_User = $argv[1];
$AD_Auth_PWD = $argv[2];
$LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "distinguishedname","streetAddress");
//Todas as OUs presentes no dominio
$TodasAsOUs = array("AL","AM","BA","CE","DF","ES","GO","MA","MG","MS","MT","PA","PB","PE","PI","PR","RJ","RN","RO","RS","SC","SE","SP","TO");

//Conectando ao AD.
$ds = ldap_connect($AD_server);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
$ver_log = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD);

if(!$ver_log){
	  	print "<p style='color:#D36F17;'>Usuário ou senha do autenticador estão incorreta(s).</p>";
}else{
	print "<table><tr><td>Estado:</td><td>Numero de usuários:</td><td>Numero de computadores:</td></tr>";
	$TotalUsers = 0; 
	$TotalComputers = 0;
	for ($i=0;$i<24;$i++){
		print "<tr><td>".$TodasAsOUs[$i]."</td>";
		
		//Contando usuários
		$DNUser = "OU=Usuarios,OU=".$TodasAsOUs[$i].",".$DC;
		$filter='(objectCategory=person)';
		$sr = ldap_search($ds, $DNUser, $filter, $LDAPFieldsToFind);
		$info = ldap_get_entries($ds, $sr);	
		print "<td><b>".$info["count"]."</b></td>";
		$TotalUsers = $TotalUsers + $info["count"];
		
		//Contando Computadores
		$DNUComputer = "OU=Estacoes,OU=".$TodasAsOUs[$i].",".$DC;
		$filter='(objectCategory=computer)';
		$sr = ldap_search($ds, $DNUComputer, $filter, $LDAPFieldsToFind);
		$info = ldap_get_entries($ds, $sr);	
		print "<td><b>".$info["count"]."</b></td></tr>";
		$TotalComputers = $TotalComputers + $info["count"];
	}
	print "<tr><td>Total</td><td><b>".$TotalUsers."</b></td><td><b>".$TotalComputers."</b></td></tr></table>";
}
?>
