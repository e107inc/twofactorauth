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
define("LAN_PLUGIN_2FA_DIZ",  "Plug-in die twee-factor-authenticatie (2FA) toevoegt aan e107"); 

// usersettings.php
define("LAN_PLUGIN_2FA_NAME_FULL", "Twee-factor-authenticatie");
define("LAN_2FA_USERSETTING_ENABLE",  "Schakel twee-factor-authenticatie in");
define("LAN_2FA_USERSETTING_DISABLE", "Schakel twee-factor-authenticatie uit");

// herstelcodes (recovery codes)
define("LAN_2FA_RECOVERY_CODES", "Twee-factor-authenticatie herstelcodes"); // used in EUF title

define("LAN_2FA_RECOVERY_CODE_USED_VALID_TITLE", 	"Succesvolle login met twee-factor-authenticatie herstelcode");
define("LAN_2FA_RECOVERY_CODE_USED_VALID_1", 		"Een twee-factor-authenticatie herstelcode is zojuist (op [x]) gebruikt om succesvol in te loggen op jouw account vanaf het IP adres [y]. Als je dit niet zelf hebt gedaan, dan is je account mogelijk niet meer goed beveiligd. Neem dan direct contact op met de websitebeheerder!");
define("LAN_2FA_RECOVERY_CODE_USED_VALID_2", 		"Je hebt nog [x] herstelcodes over. Schakel twee-factor-authenticatie uit en weer opnieuw in om nieuwe herstelcodes te genereren.");

define("LAN_2FA_RECOVERY_CODE_USED_INVALID_TITLE", 	"Mislukte poging om een twee-factor-authenticatie herstelcode te gebruiken");
define("LAN_2FA_RECOVERY_CODE_USED_INVALID_1", 		"Iemand heeft zojuist (op [x]) geprobeerd om met een twee-factor-authenticatie herstelcode in te loggen op jouw account. Deze poging was niet succesvol. Het IP adres ([y]) zal automatisch worden geblokkeerd als deze pogingen worden doorgezet.");
define("LAN_2FA_RECOVERY_CODE_USED_INVALID_2", 		"Er is geen actie van jou nodig. Neem contact op met de websitebeheerder als de pogingen door blijven gaan.");
define("LAN_2FA_RECOVERY_CODE_REACHED_FAILLIMIT", 	"TwoFactorAuth: Meer dan [x] mislukte pogingen om in te loggen met een herstelcode.");
define("LAN_2FA_RECOVERYCODES_NOT_GENERATED_YET", 	"Er zijn nog geen herstelcodes aangemaakt. Schakel twee-factor-authentica uit en weer in om deze te genereren.");