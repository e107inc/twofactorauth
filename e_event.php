<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

if (!defined('e107_INIT')) { exit; }

require_once(e_PLUGIN."twofactorauth/twofactorauth_class.php");

class twofactorauth_event 
{

	function config()
	{

		$event = array();

		// User
		$event[] = array(
			'name'		=> "user_validlogin", 
			'function'	=> "init_tfa",
		);

		return $event;

	}

	
	function init_tfa($data, $eventname) 
	{
		// Check to see if Two Factor Authentication is active for all users
		if(e107::getPlugPref('twofactorauth', 'tfa_active'))
	    {
			$tfa = new tfa_class();
			$tfa->init($data);
		}
	}

} 