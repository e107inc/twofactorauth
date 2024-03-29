<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2014 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

if (!defined('e107_INIT')) { exit; }


class twofactorauth_user 
{		
		
	function profile($udata)  // display on user profile page.
	{

	}

	/**
	 * The same field format as admin-ui, with the addition of 'fieldType', 'read', 'write', 'appliable' and 'required' as used in extended fields table.
	 *
	 * @return array
	 */
	function settings()
	{
		$fields = array();
		$fields['secret_key'] = array(
			'title' 	=> LAN_PLUGIN_2FA_NAME_FULL,  
			'read'		=> e_UC_NOBODY, 
			'write'		=> e_UC_MEMBER,
			'fieldType' => 'varchar(255)',       
			'type' 		=> 'method', 
			'data'		=> 'str', 
			'required'	=> false,
			'nolist'	=> true,
		); 

        return $fields;

	}

	function delete()
	{

	}
	
}

class twofactorauth_user_form extends e_form
{

	public function user_plugin_twofactorauth_secret_key($curVal, $mode, $att = array())
	{
		$lan = LAN_2FA_USERSETTING_ENABLE; 

		if(e107::getUserExt()->get(USERID, "user_plugin_twofactorauth_secret_key"))
		{
			$lan = LAN_2FA_USERSETTING_DISABLE; 
		}

		$url = e107::url('twofactorauth', 'setup');

		return "<a href=".$url.">".$lan."</a>";
	}
}