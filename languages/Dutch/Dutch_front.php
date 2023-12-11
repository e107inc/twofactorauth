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
define("LAN_2FA_VERIFY_INSTRUCTIONS", 		"Open de Twee Factor Authenticatie-app op uw apparaat om uw authenticatiecode te bekijken en uw identiteit te verifi&#235;ren."); 
define("LAN_2FA_ENTER_TOTP_PLACEHOLDER", 	"Voer de 6-cijferige code in"); 
define("LAN_2FA_FALLBACK_INSTRUCTIONS", 	"Als u niet de juiste authenticatiecode kunt opgeven, neem dan contact op met de websitebeheerder.");
define("LAN_2FA_INCORRECT_TOTP", 			"De door u ingevoerde 6-cijferige code is onjuist! Probeer het opnieuw.");

define("LAN_2FA_DATABASE_ERROR", 			"Er is een onbekend probleem opgetreden met betrekking tot de database. Neem dan contact op met de websitebeheerder.");


// Setup
define("LAN_2FA_ENABLE_INSTRUCTIONS1", 	"Om twee-factorenauthenticatie voor je account in te stellen, gebruikt je de [authenticator-app] op je apparaat om de onderstaande QR-code te scannen of om de geheime sleutel handmatig in te voeren."); // Do not remove the brackets [...] - they are used for links
define("LAN_2FA_ENABLE_INSTRUCTIONS2", "Om te bevestigen dat uw authenticatie-app de juiste 6-cijferige code retourneert, verifieert u de code door deze hieronder in te voeren. Na verificatie wordt twee-factor-authenticatie ingesteld voor uw account.");

define("LAN_2FA_DISABLE_INSTRUCTIONS", 	"Om tweefactorauthenticatie voor je account [b]uit te schakelen[/b], voer je ter verificatie je zescijferige code in.");

define("LAN_2FA_ENABLED", 				"Tweefactorauthenticatie is met succes [b]ingeschakeld[/b]"); 
define("LAN_2FA_DISABLED", 				"Tweefactorauthenticatie is succesvol [uitgeschakeld[/b]"); 

define("LAN_2FA_RETURN_USERSETTINGS", 	"Terug naar instellingen");