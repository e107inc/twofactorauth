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

// Only show this page when user is logged in already . 
if(!USER) 
{
	e107::redirect();
}

// Load required files (TwoFactorAuth Library and twofactorauth class)
e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
use \RobThree\Auth\TwoFactorAuth;
use \RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;

$tfa_library = new TwoFactorAuth(new EndroidQrCodeProvider());


require_once(e_PLUGIN."twofactorauth/twofactorauth_class.php");
$tfa_class = new tfa_class();

// Load LAN files
e107::lan('twofactorauth', false, true);
$caption = LAN_2FA_TITLE." - ".LAN_SETTINGS; 
e107::title($caption);

require_once(HEADERF);

$text = "";
$usersettings_url = e107::getUrl()->create('user/myprofile/edit', array('id' => USERID));

// Check if 2FA is already enabled for current user
$tfaActivated = $tfa_class->tfaActivated(USERID) ? true : false; 

// Setting up 2FA
if(!$tfaActivated && isset($_POST['enter-totp-enable']))
{
	$secret_key = (string) $_POST['secret_key']; 
	
	$totp = intval($_POST['totp']);
	$totp = (string) $totp;

	$text = '';

	if($tfa_class->processEnable(USERID, $secret_key, $totp))
	{
		e107::getMessage()->addSuccess(e107::getParser()->toHTML(LAN_2FA_ENABLED, true));

		// Check if Recovery Codes functionality is enabled, and if yes, generate new recovery codes
		if(e107::getPlugPref('twofactorauth', 'tfa_recoverycodes'))
		{
			if($recovery_codes = $tfa_class->generateRecoveryCodes(USERID))
			{
				// TODO Let user download a txt file with the codes 
				$recovery_codes1 = array_slice($recovery_codes, 0, 5);
				$recovery_codes2 = array_slice($recovery_codes, 5);

				$recovery_codes1 = implode("<br>", $recovery_codes1);
				$recovery_codes2 = implode("<br>", $recovery_codes2);


				$text .= "<p><strong>".LAN_2FA_RECOVERYCODES_GENERATED."</strong><p>";

				$text .= '<div class="row justify-content-center">';
				$text .= '	<div class="col col-md-6 text-center">
						      '.$recovery_codes1.'
						      <br>
						    </div>
					 	';
				$text .= '	<div class="col col-md-6 text-center">
								'.$recovery_codes2.'
							</div>
					 	';
				$text .= '</div>'; 
				$text .= "<br>";
			}
			else
			{
				e107::getMessage()->addError(e107::getParser()->toHTML(LAN_2FA_RECOVERYCODES_GENERATED_ERROR, true));
			}
		}

		$text .= "<a class='btn btn-primary' href='".$usersettings_url."'>".LAN_2FA_RETURN_USERSETTINGS."</a>.";

		e107::getRender()->tablerender($caption, e107::getMessage()->render().$text);
		require_once(FOOTERF);
		exit;  
	}
}

if($tfaActivated && isset($_POST['enter-totp-disable']))
{
	$totp = intval($_POST['totp']);
	$totp = (string) $totp;

	// Check if Recovery Codes are set, and if yes, remove them 
	if(e107::getPlugPref('twofactorauth', 'tfa_recoverycodes'))
	{
		if($removed = $tfa_class->removeRecoveryCodes(USERID))
		{
			// Recovery codes removed, now disable 2FA
			if($tfa_class->processDisable(USERID, $totp))
			{
				e107::getMessage()->addSuccess(e107::getParser()->toHTML(LAN_2FA_DISABLED, true));
				
				$text = "<a class='btn btn-primary' href='".$usersettings_url."'>".LAN_2FA_RETURN_USERSETTINGS."</a>.";
			}
			else
			{
				e107::getMessage()->addError(e107::getParser()->toHTML(LAN_2FA_DISABLED_ERROR, true));
			}
		}
		else
		{
			e107::getMessage()->addError(LAN_2FA_RECOVERYCODES_REMOVED_ERROR);
		}
	} 
	

	e107::getRender()->tablerender($caption, e107::getMessage()->render().$text);
	require_once(FOOTERF);
	exit;  
}


// 2FA not setup yet, show instructions 
if(!$tfaActivated)
{
	// Generate Secret Key 
	$secret = $tfa_library->createSecret(); 

	// Setup label - defaults to SITENAME
	$label = e107::getPlugPref('twofactorauth', 'tfa_label');

	if(empty($label))
	{
		$label = SITENAME;
	}

	$instructions1 = str_replace(
		array("[", "]"), 
		array("<strong><a href='https://github.com/e107inc/twofactorauth#recommended-authenticator-applications' target='_blank'>", "</a></strong>"), 
		LAN_2FA_ENABLE_INSTRUCTIONS1
	);
	
	e107::getMessage()->addInfo($instructions1);

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