<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
use \RobThree\Auth\TwoFactorAuth;

class tfa_class
{
	public $tfa_debug = false;

	public function __construct() 
	{
		// Check debug mode
		if(e107::getPlugPref('twofactorauth', 'tfa_debug')) 
		{
			$this->tfa_debug = true;
		}
	}
	
	public function init($data, $eventname)
	{
		// Login
		if($eventname == 'user_validlogin')
		{
			$user_id = $data; 
		}
		// FPW
		else
		{
			// error_log($eventname);
			// error_log(print_r($data, true));
			// return false;
			$user_id = $data["user_id"];
		}

		// Check if 2FA is activated
		if($this->tfaActivated($user_id) == false)
		{
			// 2FA is NOT activated, return false to proceed with core login process.
			if($this->tfa_debug)
			{
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": 2FA is NOT activated for User ID ".$user_id);
				e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
			}
			 
			return false; 
		}

		// 2FA is enabled for this user. Continue verification process. Service page to enter TOTP digits, generated by user's authenthicator app.
		if($this->tfa_debug)
		{
			e107::getLog()->addDebug(__LINE__." ".__METHOD__.": 2FA is activated for User ID ".$user_id);
			e107::getLog()->addDebug(__LINE__." ".__METHOD__.": User will need to enter digits. Redirect to tfa/verify");
			e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
		}

		// Store some information in a session, so we can retrieve it again later 
		e107::getSession('2fa')->set('user_id', $user_id); // Store User ID
		e107::getSession('2fa')->set('previous_page', e_REQUEST_URL); // Store the page the user is logging in from

		// Redirect to page to enter TOTP 
		$url = e107::url('twofactorauth', 'verify'); 
		e107::redirect($url);
		return true;

	}

	public function tfaActivated($user_id)
	{
		
		if(!e107::getUserExt()->get($user_id, "user_plugin_twofactorauth_secret_key"))
		{
			return false;  
		}
		
		return true; 
	}

	public function showTotpInputForm($action = 'login', $secret = '')
	{
		$text = '';

		switch($action) 
		{
			case 'login':
				$action = 'submit';
				$button_name = "enter-totp-login";
				break;
			case 'fpw':
				$action = 'submit';
				$button_name = "enter-totp-fpw";
				break;
			case 'enable':
				$action = 'submit';
				$button_name = "enter-totp-enable";
				break; 
			case 'disable':
				$action = 'delete';
				$button_name = "enter-totp-disable"; 
				break; 
			default:
				$action = 'submit';
				$button_name = "enter-totp-login";
				break;
		}

		$form_options = array(
			//"size" 		=> "small", 
			'required' 		=> 1, 
			'placeholder'	=> LAN_2FA_ENTER_TOTP_PLACEHOLDER, 
			'autofocus' 	=> true,
		);

		// Display form to enter TOTP 
		$text .= e107::getForm()->open('enter-totp');
		$text .= e107::getForm()->text("totp", "", 80, $form_options);
		
		if(!empty($secret))
		{
			$text .= e107::getForm()->hidden("secret_key", $secret);
		}
		$text .= "<br>";
		$text .= e107::getForm()->button($button_name, LAN_VERIFY, $action);
		$text .= e107::getForm()->close(); 

		return $text; 
	}

	private function verifyTotp($user_id = USERID, $totp)
	{
		$tfa_library = new TwoFactorAuth();

		// Retrieve secret_key of this user, stored in the database
		$secret_key = e107::getUserExt()->get($user_id, "user_plugin_twofactorauth_secret_key");
		
		if($this->tfa_debug)
		{
			e107::getLog()->addDebug(__LINE__." ".__METHOD__.": User ID: ".$user_id);
			e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Secret Key: ".$secret_key);
			e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Entered TOTP: ".$totp);
			e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
		}

		// Check if the entered TOTP is correct. 
		if($tfa_library->verifyCode($secret_key, $totp, 2) === true) 
		{
			// TOTP is correct. 
			if($this->tfa_debug)
			{
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": The TOTP code that was entered, is correct");
				e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
			}
			return true;
		}
		else
		{
			if($this->tfa_debug)
			{
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": The TOTP code that was entered, is INCORRECT");
				e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
			}
			return false; 
		}

	}

	public function processLogin($user_id = USERID, $totp)
	{
		if($this->verifyTotp($user_id, $totp))
		{
			// Continue processing login 
			$user = e107::user($user_id);
			$ulogin = new userlogin();
			$ulogin->validLogin($user);

			// Get previous page the user was on before logging in. 
			$redirect_to = e107::getSession('2fa')->get('previous_page');

			if($this->tfa_debug)
			{
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Session Previous page: ".$redirect_to);
				e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
			}
	
			// Clear session data
			e107::getSession('2fa')->clearData();

			// Redirect to previous page or otherwise to homepage
			if($redirect_to)
			{
				e107::getRedirect()->redirect($redirect_to);
			}
			else
			{
				e107::redirect();
			}
		
		}
		// The entered TOTP is INCORRECT
		else
		{
			return false; 
		}
	}

	public function processFpw($user_id = USERID, $totp)
	{
		if($this->verifyTotp($user_id, $totp))
		{
			return true; 
		}
		// The entered TOTP is INCORRECT
		else
		{
			return LAN_2FA_INCORRECT_TOTP; 
		}
	}

	public function processEnable($user_id = USERID, $secret_key, $totp)
	{
		$tfa_library = new TwoFactorAuth();

		// Verify code
		if($tfa_library->verifyCode($secret_key, $totp, 2) === false) 
		{
			if($this->tfa_debug)
			{
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Entered TOTP is incorrect: ".$totp);
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Secret Key: ".$secret_key);
				e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
			}

			e107::getMessage()->addError(LAN_2FA_INCORRECT_TOTP);
			return false; 
		}

		if($this->tfa_debug)
		{
			e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Entered TOTP is correct. Continue adding secret key to EUF.");
			e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
		}

		if(!e107::getUserExt()->set($user_id, "user_plugin_twofactorauth_secret_key", $secret_key))
		{
			if($this->tfa_debug)
			{
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Could not add secret key to EUF");
				e107::getLog()->addDebug(e107::getDb()->getLastErrorText());
				e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
			}

			e107::getMessage()->addError(LAN_2FA_DATABASE_ERROR);
			return false; 
		}

		if($this->tfa_debug)
		{
			e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Secret key has been added to the EUF");
			e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
		}

		return true; 
	}

	public function processDisable($user_id = USERID, $totp)
	{
		$tfa_library = new TwoFactorAuth();

		// Retrieve secret_key of this user, stored in the database
		$secret_key = e107::getUserExt()->get($user_id, "user_plugin_twofactorauth_secret_key");

		// Verify code
		if($tfa_library->verifyCode($secret_key, $totp, 2) === false) 
		{
			e107::getMessage()->addError(LAN_2FA_INCORRECT_TOTP);
			return false; 
		}

		if(!e107::getUserExt()->set($user_id, "user_plugin_twofactorauth_secret_key", ''))
		{
			if($this->tfa_debug)
			{
				e107::getLog()->addDebug(__LINE__." ".__METHOD__.": Could not empty secret_key EUF field");
				e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
			}

			e107::getMessage()->addError(LAN_2FA_DATABASE_ERROR);
			return false; 
		}

		return true; 
	}
}