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
define("LAN_2FA_TITLE", "Twee-factor-authenticatie"); 


// Verify
define("LAN_2FA_VERIFY_INSTRUCTIONS", 		"Open de twee-factor-authenticatie app op je apparaat om je authenticatiecode te bekijken en je identiteit te verifiëren."); 
define("LAN_2FA_ENTER_TOTP_PLACEHOLDER", 	"Voer de 6-cijferige code in"); 
define("LAN_2FA_FALLBACK_INSTRUCTIONS", 	"Als je niet de juiste authenticatiecode kunt opgeven, klik dan [hier] om een herstelcode te gebruiken. Mocht dat niet werken, neem dan contact op met de websitebeheerder.");
define("LAN_2FA_INCORRECT_TOTP", 			"De ingevoerde 6-cijferige code is onjuist! Probeer het opnieuw.");

define("LAN_2FA_DATABASE_ERROR", 			"Er is een onbekend probleem opgetreden met betrekking tot de database. Neem dan contact op met de websitebeheerder.");


// Recovery
define("LAN_2FA_RECOVERY", 							"Herstellen");
define("LAN_2FA_RECOVERYCODE_INSTRUCTIONS",			"Voer een van de herstelcodes in die je hebt opgeslagen toen je twee-factor-authenticatie voor je account instelde.");
define("LAN_2FA_ENTER_RECOVERYCODE_PLACEHOLDER", 	"Voer een herstelcode in");
define("LAN_2FA_INCORRECT_RECOVERYCODE", 			"Je hebt een onjuiste herstelcode ingevoerd. Neem contact op met de websitebeheerder.");
define("LAN_2FA_RECOVERYCODES_GENERATED", 			"Dit zijn de herstelcodes voor jouw account. Ze worden EENMAAL weergegeven! Sla de herstelcodes op een veilige plek op.");
define("LAN_2FA_RECOVERYCODES_GENERATED_ERROR", 	"Het is niet gelukt om de herstelcodes voor jouw account aan te maken. Neem contact op met de websitebeheerder."); 
define("LAN_2FA_RECOVERYCODES_REMOVED_ERROR", 		"Er is een probleem opgetreden bij het verwijderen van de herstelcodes voor jouw account. Neem contact op met de websitebeheerder."); 

// Setup
define("LAN_2FA_ENABLE_INSTRUCTIONS1", 	"Om twee-factor-authenticatie voor je account in te stellen, gebruikt je de [authenticator-app] op je apparaat om de onderstaande QR-code te scannen of om de geheime sleutel handmatig in te voeren."); // Do not remove the brackets [...] - they are used for links
define("LAN_2FA_ENABLE_INSTRUCTIONS2", "Om te bevestigen dat je authenticatie-app de juiste 6-cijferige code retourneert, verifieer je de code door deze hieronder in te voeren. Na verificatie wordt twee-factor-authenticatie ingesteld voor je account.");

define("LAN_2FA_DISABLE_INSTRUCTIONS", 	"Om tweefactorauthenticatie voor je account [b]uit te schakelen[/b], voer je ter verificatie je zescijferige code in.");

define("LAN_2FA_ENABLED", 				"Twee-factor-authenticatie is met succes [b]ingeschakeld[/b]"); 
define("LAN_2FA_DISABLED", 				"Twee-factor-authenticatie is succesvol [b]uitgeschakeld[/b]"); 
define("LAN_2FA_DISABLED_ERROR", 		"Er is een probleem opgetreden bij het uitschakelen van twee-factor-authenticatie. Neem contact op met de websitebeheerder.");

define("LAN_2FA_RETURN_USERSETTINGS", 	"Terug naar instellingen");