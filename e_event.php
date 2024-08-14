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

		// User login
		$event[] = array(
			'name'		=> "user_validlogin", 
			'function'	=> "init_tfa",
		);

		// User has submitted Forgotten Password form
		$event[] = array(
			'name'		=> "user_fpw_request", 
			'function'	=> "init_tfa",
		);

		// User has submitted an recovery code (either valid or invalid)
		$event[] = array(
			'name'		=> "twofactorauth_recovery_code_used", 
			'function'	=> "recovery_code_used",
		);

		return $event;
	}

	
	function init_tfa($data, $eventname) 
	{
		// Check to see if Two Factor Authentication is active for all users
		if(e107::getPlugPref('twofactorauth', 'tfa_active'))
	    {
			$tfa_class = new tfa_class();
			$tfa_class->tfaDebug(__LINE__." ".__FILE__.": Start Initialising TFA code.");
			$tfa_class->tfaDebug(__LINE__." ".__FILE__.": Eventname: ".$eventname);
			$tfa_class->init($data, $eventname);
		}
	}

	function recovery_code_used($data, $eventname)
	{
		$tfa = new tfa_class();
		$tfa_class->tfaDebug(__LINE__." ".__FILE__.": Start recovery code notification");

		$userdata = e107::user($data['user_id']); 

		//$message = print_a($data, true);
		//$message .= print_a($userdata, true);

		$timestamp = e107::getDate()->convert_date(time(), 'long');

		$message = '';

		// Recovery was valid
		if($data["valid"])
		{
			$tfa_class->tfaDebug(__LINE__." ".__FILE__.": Recovery code was valid");

			$subject = LAN_2FA_RECOVERY_CODE_USED_VALID_TITLE;

			$message .= e107::getParser()->lanVars(LAN_2FA_RECOVERY_CODE_USED_VALID_1, array('x' => $timestamp, 'y'=> $data["user_ip"]), true);
			$message .= "<br><br>";
			$message .= e107::getParser()->lanVars(LAN_2FA_RECOVERY_CODE_USED_VALID_2, array('x'=> $data["remaining"]), true);
		}
		// Recovery code was invalid
		else
		{
			$tfa_class->tfaDebug(__LINE__." ".__FILE__.": Recovery code was invalid");

			$subject = LAN_2FA_RECOVERY_CODE_USED_INVALID_TITLE; 

			$message .= e107::getParser()->lanVars(LAN_2FA_RECOVERY_CODE_USED_INVALID_1, array('x' => $timestamp, 'y'=> $data["user_ip"]), true);
			$message .= "<br><br>";
			$message .= LAN_2FA_RECOVERY_CODE_USED_INVALID_2;
		}


		$eml = array(
			'subject' 		=> $subject,
			'sender_name'	=> SITENAME,
			'html'			=> true,
			'template'		=> 'default',
			'body'			=> $message
		);

		e107::getEmail()->sendEmail($userdata["user_email"], $userdata["user_name"], $eml);

		$tfa_class->tfaDebug(__LINE__." ".__FILE__.": Recovery code notification email sent");
	}
} 