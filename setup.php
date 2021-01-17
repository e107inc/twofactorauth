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

// Only show this page when user is logged in already . 
if(!USER) 
{
	e107::redirect(e_BASE.'login.php'); 
}

e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
use \RobThree\Auth\TwoFactorAuth;
$tfa_library = new TwoFactorAuth();

require_once(e_PLUGIN."twofactorauth/twofactorauth_class.php");
$tfa_class = new tfa_class();

require_once(HEADERF);
$text = "";

// Check if 2FA is already enabled for current user
$tfaActivated = $tfa_class->tfaActivated(USERID) ? true : false; 

// Setting up 2FA
if(!$tfaActivated && isset($_POST['enter-totp-enable']))
{
	$secret_key = $_POST['secret_key']; // TODO - add some validation?
	$totp 		= $_POST['totp']; // TODO - add some validation?

	if($tfa_class->processSetup(USERID, $secret_key, $totp))
	{
		e107::getMessage()->addSuccess("2FA succesfully <strong>enabled.</strong>");
		$text = "Go to homepage button?";

		e107::getRender()->tablerender("Two Factor Authenthication - Setup", e107::getMessage()->render().$text);
		require_once(FOOTERF);
		exit;  
	}
}

if($tfaActivated && isset($_POST['disable-2fa']))
{
	$totp = $_POST['totp']; // TODO - add some validation?

	if($tfa_class->processDisable(USERID, $totp))
	{
		e107::getMessage()->addSuccess("2FA succesfully <strong>disabled</strong>.");
		$text = "Go to homepage button?";

		e107::getRender()->tablerender("Two Factor Authenthication - Setup", e107::getMessage()->render().$text);
		require_once(FOOTERF);
		exit;  
	}
}


// 2FA not setup yet, show instructions 
if(!$tfaActivated)
{
	// Generate Secret Key 
	$secret = $tfa_library->createSecret(160); 

	// Sitename - TODO make it a pref?
	$label = SITENAME;  

	$text .= 'To set up Two Factor Authenthication for your account, please scan the below QR code or enter the secret key manually in your authenticator app. <br>';
	$text .= '<img class="center-block" src="' . $tfa_library->getQRCodeImageAsDataUri($label, $secret) . '"><br>';
	$text .= '<span class="center-block">'.chunk_split($secret, 4, ' ').'</span><br>';

	$text .= 'Please confirm the TOTP code by entering it below. When the code is correct, Two Factor Authenthication is setup for your account.<br><br>';

	$text .= $tfa_class->showTotpInputForm('enable', $secret); 

}
// 2FA already activated, show options to disable. 
else
{
	$text .= "Already activated, To disable Enter TOTP."; // TODO
	$text .= $tfa_class->showTotpInputForm('disable'); 

}

// Let's render and show it all!
e107::getRender()->tablerender("Two Factor Authenthication - Setup", e107::getMessage()->render().$text);
require_once(FOOTERF);
exit;