<?php

function hesk_password_verify($password, $hash, $userId = null)
{
	global $hesk_settings, $hesk_ldap_settings, $hesklang;
	
	if($userId == null){
		$userId = $_SESSION['id'];
	}
	
	$res = hesk_dbQuery("SELECT ldap_dn FROM `" . hesk_dbEscape($hesk_settings['db_pfix'])."plugin_ldap` WHERE user_id=".hesk_dbEscape($userId));
	
    if ($row = hesk_dbFetchAssoc($res)){
		$ldap_dn = $row["ldap_dn"];
		
		if ($connect=ldap_connect($hesk_ldap_settings['server_host'], $hesk_ldap_settings['server_port'])) {
			ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, $hesk_ldap_settings['ldap_version']);
			ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
			
			if ($bind=ldap_bind($connect, $ldap_dn, $password)) {
				return true;
			}
		}
    }
	
	return false;
}

function hesk_password_needs_rehash($hash)
{
    return false;
}

function getLdapPluginPatchVersion()
{
    return 1.0;
}