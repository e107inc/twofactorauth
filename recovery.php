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
	if($tfa_class->checkDebug())
	{
		e107::getLog()->addDebug(__LINE__." ".__FILE__.": session_user_id: ".$session_user_id);
		e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
	}

	if(USER)
	{
		if($tfa_class->checkDebug())
		{
			e107::getLog()->addDebug(__LINE__." ".__FILE__.": User is already logged in? Redirect to setup");
			e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
		}

		$url = e107::url('twofactorauth', 'setup'); 
		e107::redirect($url);
	}
	else
	{
		if($tfa_class->checkDebug())
		{
			e107::getLog()->addDebug(__LINE__." ".__FILE__.": session user id already set? Redirect to homepage");
			e107::getLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
		}
		e107::redirect();
	}

	e107::redirect($url);
	exit;
}

// Load LAN files
e107::lan('twofactorauth', false, true);
$caption = LAN_2FA_TITLE." - ".LAN_2FA_RECOVERY;
e107::title($caption);

require_once(HEADERF);
$text = "";

// Process TOTP code and verify against secret key
if(isset($_POST['enter-recovery-code']))
{
	// Retrieve user ID from session 
	$user_id 		= e107::getSession('2fa')->get('user_id');
	$recovery_code 	= (string) $_POST['recovery-code']; 

	if(!$tfa_class->processRecoveryCode($user_id, $recovery_code))
	{
		e107::getMessage()->addError(LAN_2FA_INCORRECT_RECOVERYCODE); 
	}
}

// Display form to enter recovery code
e107::getMessage()->addInfo(e107::getParser()->toHTML(LAN_2FA_RECOVERYCODE_INSTRUCTIONS, true));
$text .= $tfa_class->showRecoveryCodeInputForm(); 


// Let's render and show it all!
e107::getRender()->tablerender($caption, e107::getMessage()->render().$text);

require_once(FOOTERF);
exit;