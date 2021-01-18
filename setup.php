<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

if(!defined('e107_INIT'))
{
	require_once("../../class2.php");
}

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

// Load required files (TwoFactorAuth Library and twofactorauth class)
e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
use \RobThree\Auth\TwoFactorAuth;
$tfa_library = new TwoFactorAuth();

require_once(e_PLUGIN."twofactorauth/twofactorauth_class.php");
$tfa_class = new tfa_class();

// Load LAN files
e107::lan('twofactorauth', false, true);
$caption = LAN_2FA_TITLE." - ".LAN_SETTINGS; 
e107::title($caption);

require_once(HEADERF);

$text = "";

// Check if 2FA is already enabled for current user
$tfaActivated = $tfa_class->tfaActivated(USERID) ? true : false; 

// Setting up 2FA
if(!$tfaActivated && isset($_POST['enter-totp-enable']))
{
	$secret_key = $_POST['secret_key']; // TODO - add some validation?
	$totp 		= $_POST['totp']; // TODO - add some validation?

	if($tfa_class->processEnable(USERID, $secret_key, $totp))
	{
		e107::getMessage()->addSuccess(e107::getParser()->toHTML(LAN_2FA_ENABLED, true));
		$text = "Go to homepage button?"; // TODO

		e107::getRender()->tablerender($caption, e107::getMessage()->render().$text);
		require_once(FOOTERF);
		exit;  
	}
}

if($tfaActivated && isset($_POST['enter-totp-disable']))
{
	$totp = $_POST['totp']; // TODO - add some validation?

	if($tfa_class->processDisable(USERID, $totp))
	{
		e107::getMessage()->addSuccess(e107::getParser()->toHTML(LAN_2FA_DISABLED, true));
		$text = "Go to homepage button?"; // TODO

		e107::getRender()->tablerender($caption, e107::getMessage()->render().$text);
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
	$label = e107::getPlugPref('twofactorauth', 'tfa_label', SITENAME);

	e107::getMessage()->addInfo(e107::getParser()->toHTML(LAN_2FA_ENABLE_INSTRUCTIONS1, true));

	$text .= '<img class="center-block" src="' . $tfa_library->getQRCodeImageAsDataUri($label, $secret) . '"><br>';
	$text .= '<p class="text-center font-italic">'.chunk_split($secret, 4, ' ').'</p>';
	$text .= '<p>'.LAN_2FA_ENABLE_INSTRUCTIONS2.'</p>';

	$text .= $tfa_class->showTotpInputForm('enable', $secret); 

	// TEMP FOR DEV PURPOSES
	// $correct_totp 	= $tfa_library->getCode($secret);
	// $text 			.= $correct_totp; 

}
// 2FA is already activated, show option(s) to disable. 
else
{
	e107::getMessage()->addInfo(e107::getParser()->toHTML(LAN_2FA_DISABLE_INSTRUCTIONS, true));
	$text .= $tfa_class->showTotpInputForm('disable'); 
}

// Let's render and show it all!
e107::getRender()->tablerender($caption, e107::getMessage()->render().$text);
require_once(FOOTERF);
exit;