<?php
include 'selad.php';
$AD_Auth_User = $argv[1];
$AD_Auth_PWD = $argv[2];
$LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "distinguishedname");
$SearchFor=$argv[3];
$SearchField="samaccountname";
$filter="($SearchField=$SearchFor)";
//Defini qual o endere�o da OU de desativados
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
  }
  if (!$ver_log){
  	print "<p style='color:#D36F17;'>Usuário ou senha do autenticador está incorreta(s).</p>";
  }elseif ($x==0) {
	print "<p style='color:#C93434;'>Erro, login <b>$SearchFor</b> não foi encontrado. Por favor tente de novo.</p>"; 
  }else{

        //system("net ads password " . $SearchFor . "@INTRANET Nova" . $ano . " -U" .$AD_Auth_User. "%" . $AD_Auth_PWD . " 2&> /dev/null");

        if ($ds) {
           ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
           ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
           $r = ldap_bind($ds, $AD_Auth_User.$LDAPUserDomain, $AD_Auth_PWD); //BIND

           $ldaprecord2['UserAccountControl'] = "514";
           $newOU = 'OU=Usuarios,OU=Desativados,OU=DNIT,DC=intranet';
           
           $r = ldap_mod_replace($ds, $dn, $ldaprecord2);
           $mo = ldap_rename($ds, $dn, 'cn=' . $nam, $OUDesativados, true);
           
           if ($r and $mo){
           	print "<p style='color:#2CA823;'>Usuário <b>$nam</b> desativado e movido para OU=Desativados com sucesso.</p>";
            
	       }else{
	       	print "<p style='color:#C93434;'>Erro, usuário <b>$nam NÃO</b> desativado com sucesso.</p>";
		   }
        } else {
        	echo "<p style='color:#D36F17;'><b>Erro</b>, não foi possivel conectar no servidor LDAP: $AD_server.</p>";
        }

       }
?>