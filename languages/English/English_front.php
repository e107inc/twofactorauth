<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

// Page titles
define("LAN_2FA_TITLE", "Two Factor Authentication"); 


// Verify
define("LAN_2FA_VERIFY_INSTRUCTIONS", 		"Open the Two Factor Authentication app on your device to view your authentication code and verify your identity."); 
define("LAN_2FA_ENTER_TOTP_PLACEHOLDER", 	"Enter the 6-digit code"); 
define("LAN_2FA_FALLBACK_INSTRUCTIONS", 	"If you are unable to provide the correct authentication code, click [here] to use a recovery code. If that does not work, please contact the website administrator.");
define("LAN_2FA_INCORRECT_TOTP", 			"The 6-digit code you entered is incorrect! Please try again.");

define("LAN_2FA_DATABASE_ERROR", 			"Unknown issue occurred related to the database. Please contact the website administrator.");


// Recovery
define("LAN_2FA_RECOVERY", 							"Recovery");
define("LAN_2FA_RECOVERYCODE_INSTRUCTIONS",			"Please enter one of the recovery codes that you saved when setting up Two Factor Authenthication.");
define("LAN_2FA_ENTER_RECOVERYCODE_PLACEHOLDER", 	"Enter a recovery code");
define("LAN_2FA_INCORRECT_RECOVERYCODE", 			"You have not entered a correct recovery code. Please contact the website administrator.");
define("LAN_2FA_RECOVERYCODES_GENERATED", 			"These are the recovery codes for your account. They are only shown ONCE! Please make sure to save them in a secure spot.");
define("LAN_2FA_RECOVERYCODES_GENERATED_ERROR", 	"There was an error generating the recovery codes for your account. Please contact the website administrator."); 


// Setup
define("LAN_2FA_ENABLE_INSTRUCTIONS1", 	"To set up Two Factor Authenthication for your account, please use the [authenticator app] on your device to scan the QR code below or to enter the secret key manually."); // Do not remove the brackets [...] - they are used for links
define("LAN_2FA_ENABLE_INSTRUCTIONS2", "To confirm your authenticator app is returning the correct 6-digit code, please verify the code by entering it below. After verification, Two Factor Authentication is setup for your account.");

define("LAN_2FA_DISABLE_INSTRUCTIONS", 	"To [b]disable[/b] Two Factor Authenthication for your account, please enter your 6-digit code to verify.");

define("LAN_2FA_ENABLED", 				"Two Factor Authenthication has been succesfully [b]enabled[/b]"); 
define("LAN_2FA_DISABLED", 				"Two Factor Authenthication has been succesfully [b]disabled[/b]"); 

define("LAN_2FA_RETURN_USERSETTINGS", 	"Return to settings");