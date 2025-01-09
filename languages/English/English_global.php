<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

// plugin.xml
define("LAN_PLUGIN_2FA_NAME", "TwoFactorAuth");
define("LAN_PLUGIN_2FA_DIZ",  "Plugin that adds Two-Factor Authentication (2FA) to e107"); 

// usersettings.php
define("LAN_PLUGIN_2FA_NAME_FULL", "Two Factor Authentication");
define("LAN_2FA_USERSETTING_ENABLE",  "Enable Two Factor Authenthication");
define("LAN_2FA_USERSETTING_DISABLE", "Disable Two Factor Authenthication");

// Recovery codes
define("LAN_2FA_RECOVERY_CODES", "Two Factor Authenthication Recovery Codes"); // used in EUF title

define("LAN_2FA_RECOVERY_CODE_USED_VALID_TITLE", 	"Successful login with Two Factor Authenthication Recovery Code");
define("LAN_2FA_RECOVERY_CODE_USED_VALID_1", 		"A Two Factor Authenthication Recovery Code was just used (at [x]) to successfully login to your account from IP address [y]. If this was not you, your account may be compromised. Please contact the website administrator immediately!");
define("LAN_2FA_RECOVERY_CODE_USED_VALID_2", 		"You have [x] recovery codes remaining. To generate new recovery codes, please disable and re-enable Two Factor Authenthication on your account.");

define("LAN_2FA_RECOVERY_CODE_USED_INVALID_TITLE", 	"Failed attempt to use Two Factor Authenthication Recovery Code");
define("LAN_2FA_RECOVERY_CODE_USED_INVALID_1", 		"Someone just tried to login to your account (at [x]) using a Two Factor Authenthication Recovery Code. This attempt was unsuccessful. Their IP address ([y]) will be banned automatically should the attempts continue.");
define("LAN_2FA_RECOVERY_CODE_USED_INVALID_2", 		"There is no action needed on your part. If the issue persists, please contact the website administrator.");
define("LAN_2FA_RECOVERY_CODE_REACHED_FAILLIMIT", 	"TwoFactorAuth: More than [x] failed attempts to login using a recovery code.");
define("LAN_2FA_RECOVERYCODES_NOT_GENERATED_YET", 	"There are no recovery codes set yet. Please disable and re-enable Two Factor Authentication to generate them."); 

// Event logging
define("LAN_2FA_TFA_01", "TFA Enabled"); 
define("LAN_2FA_TFA_02", "TFA Disabled"); 
define("LAN_2FA_TFA_03", "TFA TOTP valid"); 
define("LAN_2FA_TFA_04", "TFA TOTP invalid"); 
define("LAN_2FA_TFA_05", "TFA Recovery Code valid"); 
define("LAN_2FA_TFA_06", "TFA Recovery Code invalid"); 
define("LAN_2FA_TFA_07", "TFA Recovery Code floodlimit"); 