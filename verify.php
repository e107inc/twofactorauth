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
	require_once(__DIR__.'/../../class2.php');
}

// Make this page inaccessible when plugin is not installed. 
if (!e107::isInstalled('twofactorauth'))
{
	e107::redirect();
	exit;
}

require_once(e_PLUGIN."twofactorauth/twofactorauth_class.php");
$tfa_class = new tfa_class();

$session_user_id 		= e107::getSession('2fa')->get('user_id');
$session_previous_page 	= e107::getSession('2fa')->get('previous_page');

// No need to access this file directly or when already logged in. 
if(empty($session_user_id) || USER)
{
	$tfa_class->tfaDebug(__LINE__." ".__FILE__.": session_user_id: ".$session_user_id);

	if(USER)
	{
		$tfa_class->tfaDebug(__LINE__." ".__FILE__.": User is already logged in? Redirect to setup");

		$url = e107::url('twofactorauth', 'setup'); 
		e107::redirect($url);
	}
	else
	{
		$tfa_class->tfaDebug(__LINE__." ".__FILE__.": session user id already set? Redirect to homepage");
		e107::redirect();
	}

	e107::redirect($url);
	exit;
}

// Check action
if(str_contains($session_previous_page, 'fpw.php')) 
{
	$action = 'fpw';
}
else
{
	$action = 'login';
}
;

// Load LAN files
e107::lan('twofactorauth', false, true);
$caption = LAN_2FA_TITLE." - ".LAN_VERIFY;
e107::title($caption);

require_once(HEADERF);
$text = "";

// Process TOTP code and verify against secret key
if(isset($_POST))
{
	// Retrieve user ID from session 
	$user_id = e107::getSession('2fa')->get('user_id');

	// Set $totp, entered by user
	$totp = intval($_POST['totp']);
	$totp = (string) $totp;

	if(isset($_POST['enter-totp-login']))
	{
		if(!$tfa_class->processLogin($user_id, $totp))
		{
			e107::getMessage()->addError(LAN_2FA_INCORRECT_TOTP); 
		}
	}

	if(isset($_POST['enter-totp-fpw']))
	{
		$tfa_class->tfaDebug(__LINE__." ".__FILE__.": Start running processFpw");

		if(!$tfa_class->processFpw($user_id, $totp))
		{
			e107::getMessage()->addError(LAN_2FA_INCORRECT_TOTP); 
		}
		else
		{
			$tfa_class->tfaDebug(__LINE__." ".__FILE__.": FPW - TOTP is correct. Return true.");

			return true; 
		}
	}
	
}

// TEMP FOR DEV PURPOSES
// $secret 		= e107::getDB()->retrieve('twofactorauth', 'secret_key', "user_id='1'");
// $correct_totp 	= $tfa_library->getCode($secret);
// $text 			.= $correct_totp; 

// Display form to enter TOTP 
e107::getMessage()->addInfo(e107::getParser()->toHTML(LAN_2FA_VERIFY_INSTRUCTIONS, true));
$text .= $tfa_class->showTotpInputForm($action); 

$fallback_instructions = str_replace(['[', ']'], ["<a href='".e107::url('twofactorauth', 'recovery')."'>", '</a>'], LAN_2FA_FALLBACK_INSTRUCTIONS);

$text .= '<p class="font-italic">'.$fallback_instructions.'</p>';


// Let's render and show it all!
e107::getRender()->tablerender($caption, e107::getMessage()->render().$text);

require_once(FOOTERF);
exit;