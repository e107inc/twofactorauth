<?php
/*
 * 2FA - an e107 plugin by Tijn Kuyper
 *
 * Copyright (C) 2021-2022 Tijn Kuyper (http://www.tijnkuyper.nl)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

if (!defined('e107_INIT')) { exit; }

class tfa_event 
{

	function config()
	{

		$event = array();

		// User
		$event[] = array(
			'name'		=> "preuserlogin", 
			'function'	=> "init_2fa",
		);

		return $event;

	}

	
	function init_2fa($data, $eventname) 
	{
		error_log($data);
		return "testsfesfe";
	}

} 