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

// Load required files (TwoFactorAuth Library and twofactorauth class)
// e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
// use \RobThree\Auth\TwoFactorAuth;
// $tfa_library = new TwoFactorAuth();

require_once(e_PLUGIN."twofactorauth/twofactorauth_class.php");
$tfa_class = new tfa_class();

require_once(HEADERF);
$text = "";

// Process TOTP code and verify against secret key
if(isset($_POST['enter-totp-login']))
{
	// Retrieve user ID from session 
	$user_id = e107::getSession('2fa')->get('user_id');
	error_log("Session User ID: ".$user_id);

	// Set $totp, entered by user
	$totp = $_POST['totp']; // TODO check input for digits only
	error_log("TOTP entered: ".$totp);

	if(!$tfa_class->processLogin($user_id, $totp))
	{
		e107::getMessage()->addError("Invalid TOTP. Please retry."); 
	}
}

// TEMP FOR DEV PURPOSES
// $secret 		= e107::getDB()->retrieve('twofactorauth', 'secret_key', "user_id='1'");
// $correct_totp 	= $tfa_library->getCode($secret);
// $text 			.= $correct_totp; 

// Display form to enter TOTP 
$text .= $tfa_class->showTotpInputForm(); 

// Let's render and show it all!
e107::getRender()->tablerender("Two Factor Authenthication - Login", e107::getMessage()->render().$text);

require_once(FOOTERF);
exit;