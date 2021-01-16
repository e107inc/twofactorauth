<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

// Make this page inaccessible when plugin is not installed. 
/*if (!e107::isInstalled('twofactorauth'))
{
	e107::redirect();
	exit;
}*/

require_once("../../class2.php");

// No need to access this file directly. 
// TODO check if redirected from core login, else redirect to core login first. 

e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
use \RobThree\Auth\TwoFactorAuth;
$tfa_library = new TwoFactorAuth();

require_once(HEADERF);

$sql = e107::getDb();
$tp  = e107::getParser();
$frm = e107::getForm(); 

$text = "";


// Process TOTP code and verify against secret key
if(isset($_POST['enter-totp-process']))
{
	// Retrieve user ID from session 
	$user_id 	= e107::getSession('2fa')->get('user_id');
	error_log("Session User ID: ".$user_id);

	// Retrieve secret_key of this user, stored in the database
	$secret_key = e107::getDB()->retrieve('twofactorauth', 'secret_key', "user_id='{$user_id}'");
	error_log("Secret key: ".$secret_key);

	// Set $totp, entered by user
	$totp = $_POST['totp'];
	error_log("TOTP entered: ".$totp);

	// Check if the entered TOTP is correct. 
	if($tfa_library->verifyCode($secret_key, $totp) === true) 
	{
		// TOTP is correct. 
		error_log("TOTP IS VERIFIED");

		// Continue processing login 
		$user = e107::user($user_id); 
		e107::getUserSession()->makeUserCookie($user);

		// Trigger login event
		$login_event_data = array(
			'user_id' 		=> $user['user_id'], 
			'user_name' 	=> $user['user_name'], 
			'class_list' 	=> $user['class_list'], 
			'remember_me' 	=> 0, 
			'user_admin'	=> $user['user_admin'], 
			'user_email'	=> $user['user_email'],
		);

		e107::getEvent()->trigger("login", $login_event_data);

		// Get previous page the user was on before logging in. 
		$redirect_to = e107::getSession('2fa')->get('previous_page');
		error_log("Session Previous page: ".$redirect_to); 

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
$text .= e107::getFOrm()->close(); 

// Let's render and show it all!
e107::getRender()->tablerender("Two Factor Authenthication", e107::getMessage()->render().$text);


require_once(FOOTERF);
exit;