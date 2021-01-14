<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

// Make this page inaccessible when plugin is not installed. 
/*if (!e107::isInstalled('twofactorauth'))
{
	e107::redirect();
	exit;
}*/

require_once("../../class2.php");

// Load vendor package
e107_require_once(e_PLUGIN.'twofactorauth/vendor/autoload.php');
use \RobThree\Auth\TwoFactorAuth;

require_once(HEADERF);

$sql = e107::getDb();
$tp  = e107::getParser();


$tfa = new TwoFactorAuth("test");


       

echo '<li>First create a secret and associate it with a user';
$secret = $tfa->createSecret(160);  // Though the default is an 80 bits secret (for backwards compatibility reasons) we recommend creating 160+ bits secrets (see RFC 4226 - Algorithm Requirements)
echo '<li>Next create a QR code and let the user scan it:<br><img src="' . $tfa->getQRCodeImageAsDataUri('My label', $secret) . '"><br>...or display the secret to the user for manual entry: ' . chunk_split($secret, 4, ' ');
$code = $tfa->getCode($secret);
echo '<li>Next, have the user verify the code; at this time the code displayed by a 2FA-app would be: <span style="color:#00c">' . $code . '</span> (but that changes periodically)';
echo '<li>When the code checks out, 2FA can be / is enabled; store (encrypted?) secret with user and have the user verify a code each time a new session is started.';
echo '<li>When aforementioned code (' . $code . ') was entered, the result would be: ' . (($tfa->verifyCode($secret, $code) === true) ? '<span style="color:#0c0">OK</span>' : '<span style="color:#c00">FAIL</span>');



    
/*
$text = "testing";
// Let's render and show it all!
e107::getRender()->tablerender("2FA", $text);
*/

require_once(FOOTERF);
exit;