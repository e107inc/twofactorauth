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

class twofactorauth_url 
{
	function config() 
	{
		$config = array();

		$config['login'] = array(
			'regex'			=> '^tfa/login/?$',
			'sef'			=> 'tfa/login',
			'redirect'		=> '{e_PLUGIN}twofactorauth/login.php',
		);

		$config['setup'] = array(
			'regex'			=> '^tfa/setup/?$',
			'sef'			=> 'tfa/setup',
			'redirect'		=> '{e_PLUGIN}twofactorauth/setup.php',
		);

		return $config;
	}
}