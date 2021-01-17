<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */
require_once("../../class2.php");

// Make this page inaccessible when plugin is not installed. 
if (!e107::isInstalled('twofactorauth'))
{
	e107::redirect();
	exit;
}

$session_user_id = e107::getSession('2fa')->get('user_id');

// No need to access this file directly or when already logged in. 
if(empty($session_user_id) || USER)
{
	if(USER)
	{
		e107::redirect(e_BASE.'usersettings.php'); 
	}
	else
	{
		e107::redirect(e_BASE.'login.php'); 
	}
	exit;
}

e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
use \RobThree\Auth\TwoFactorAuth;
$tfa_library = new TwoFactorAuth();

require_once(HEADERF);
$text = "";

// Process TOTP code and verify against secret key
if(isset($_POST['enter-totp-process']))
{
	// Retrieve user ID from session 
	$user_id = e107::getSession('2fa')->get('user_id');
	error_log("Session User ID: ".$user_id);

	// Retrieve secret_key of this user, stored in the database
	$secret_key = e107::getDB()->retrieve('twofactorauth', 'secret_key', "user_id='{$user_id}'");
	error_log("Secret key: ".$secret_key);

	// Set $totp, entered by user
	$totp = $_POST['totp']; // TODO check input for digits only
	error_log("TOTP entered: ".$totp);

	// Check if the entered TOTP is correct. 
	if($tfa_library->verifyCode($secret_key, $totp) === true) 
	{
		// TOTP is correct. 
		error_log("TOTP IS VERIFIED");

		// Continue processing login 
		$user = e107::user($user_id); 
		e107::getUserSession()->validLogin($user);

		// Get previous page the user was on before logging in. 
		$redirect_to = e107::getSession('2fa')->get('previous_page');
		error_log("Session Previous page: ".$redirect_to); 

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
	// The entered TOTP is incorrect 
	else
	{
		error_log("TOTP IS INVALID");
		e107::getMessage()->addError("Invalid TOTP. Please retry."); 
	}

}

$form_options = array(
	//"size" 		=> "small", 
	'required' 		=> 1, 
	'placeholder'	=> "Enter 2FA code", 
	'autofocus' 	=> true,
);

// Display form to enter TOTP 
$text .= e107::getForm()->open('enter-totp');
$text .= e107::getForm()->text("totp", "", 80, $form_options);
$text .= e107::getForm()->button('enter-totp-process', "Submit 2FA code");
$text .= e107::getForm()->close(); 

// Let's render and show it all!
e107::getRender()->tablerender("Two Factor Authenthication", e107::getMessage()->render().$text);

require_once(FOOTERF);
exit;