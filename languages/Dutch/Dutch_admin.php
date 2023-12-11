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
define("LAN_2FA_PREFS_ACTIVE", 			"TFA Actief");
define("LAN_2FA_PREFS_ACTIVE_HELP", 	"Maakt het mogelijk om twee-factor-authenticatie in of uit te schakelen voor alle gebruikers.");

define("LAN_2FA_PREFS_DEBUG", 			"TFA-foutopsporingsmodus");
define("LAN_2FA_PREFS_DEBUG_HELP", 		"Indien ingeschakeld, worden er logbestanden gegenereerd die kunnen helpen bij het opsporen van problemen.");

define("LAN_2FA_PREFS_WEBLABEL", 		"Website label");
define("LAN_2FA_PREFS_WEBLABEL_HELP", 	"Dit wordt gebruikt in de authenticatie app om uw website te labelen. Standaard ingesteld op SITENAME zoals ingesteld in voorkeuren");


// Disable process
define("LAN_2FA_DISABLE_ALREADY_DISABLED", 	"Twee-factor-authenticatie is al uitgeschakeld voor gebruikers-ID [x]... Dat is vreemd!");
define("LAN_2FA_DISABLE_SUCCESS", 			"Twee-factor-authenticatie is uitgeschakeld voor gebruikers-ID [x]");
define("LAN_2FA_DISABLE_ERROR", 			"Kan twee-factor-authenticatie niet uitschakelen voor gebruikers-ID [x]");

define("LAN_2FA_DISABLE_BATCH",             "Schakel twee-factor-authenticatie uit voor geselecteerde gebruikers");

// Help
define("LAN_2FA_HELP_MANAGE", 	"De tabel rechts toont elke gebruiker die twee-factor-authenticatie op zijn account heeft geactiveerd.");
define("LAN_2FA_HELP_DISABLE1", "Als beheerder kunt u twee-factor-authenticatie voor elke gebruiker uitschakelen door op het kruisje te klikken.");
define("LAN_2FA_HELP_DISABLE2", "Dit kan handig zijn als een gebruiker niet in staat is de juiste authenticatiecode op te halen en daardoor geen toegang heeft tot zijn account.");