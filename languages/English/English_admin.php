<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

// Prefs
define("LAN_2FA_PREFS_ACTIVE", 			"TFA Active");
define("LAN_2FA_PREFS_ACTIVE_HELP", 	"Allows to Two Factor Authentication on or off for all users.");

define("LAN_2FA_PREFS_DEBUG", 			"TFA Debug Mode");
define("LAN_2FA_PREFS_DEBUG_HELP", 		"When enabled, log files are generated which can help debug issues.");

define("LAN_2FA_PREFS_WEBLABEL", 		"Website Label");
define("LAN_2FA_PREFS_WEBLABEL_HELP", 	"This is used in the authenticator device to label your Website. Defaults to SITENAME as set in Preferences");


// Disable process
define("LAN_2FA_DISABLE_ALREADY_DISABLED", 	"Two Factor Authentication is already disabled for User ID [x]... That's odd!");
define("LAN_2FA_DISABLE_SUCCESS", 			"Two Factor Authentication has been disabled for User ID [x]");
define("LAN_2FA_DISABLE_ERROR", 			"Could not disable Two Factor Authentication for User ID [x]");

define("LAN_2FA_DISABLE_BATCH",             "Disable Two Factor Authentication for selected users");

// Help
define("LAN_2FA_HELP_MANAGE", 	"The table on right right shows every user who has activated Two Factor Authentication on their account.");
define("LAN_2FA_HELP_DISABLE1", "As an admin, you can disable Two Factor Authentication for each user by clicking the cross icon.");
define("LAN_2FA_HELP_DISABLE2", "This may be useful when a user is not able to retrieve the correct authentication code, and is therefore unable to access their account.");