<?php
/*
 * 2FA - an e107 plugin by Tijn Kuyper
 *
 * Copyright (C) 2021-2022 Tijn Kuyper (http://www.tijnkuyper.nl)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

//$tfaActive = e107::pref('2fa', 'active');
$tfaActive = true; // for dev purposes

if($tfaActive)
{
	e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');

	e107::getOverride()->replace('secure_image::r_image',     'tfa_module::blank');
	e107::getOverride()->replace('secure_image::renderInput', 'tfa_module::input');
	e107::getOverride()->replace('secure_image::invalidCode', 'tfa_module::invalid'); 
	e107::getOverride()->replace('secure_image::renderLabel', 'tfa_module::label'); 
	e107::getOverride()->replace('secure_image::verify_code', 'tfa_module::verify'); // verify process
}


class twofactorauth_module
{
	function __construct()
	{
		e107::lan('2fa', false, true);
	}


	static function blank()
	{
		return;
	}

	static function input()
	{
		return e107::getForm()->text("code_verify", "", 20, array( "size"=> 20, 'required'=> 1, 'placeholder'=> "Enter 2FA code"));
	}

	static function label()
	{
		return "Enter 2FA code";
	}

	static function verify($code, $other)
	{
		return (self::invalid()) ? false : true;
	}

	/**
	 * @return bool - error message if it failed to validate and false if it succeeded.
	 */
	static function invalid($rec_num, $input)
	{
	
		error_log("2FA input: ".$input);


		// $code =  $_POST['2fa_code']
		// $secret = $_POST['2fa_code'] (testing purposes)

		$tfa = new \RobThree\Auth\TwoFactorAuth();

		if($tfa->verifyCode($secret, $input) === true)
		{
			return false;
		}
		else
		{
			return "Incorrect 2FA code";	
		}
	}
}


?>