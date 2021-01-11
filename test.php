<?php
/*
 * 2FA - an e107 plugin by Tijn Kuyper
 *
 * Copyright (C) 2021-2022 Tijn Kuyper (http://www.tijnkuyper.nl)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

require_once("../../class2.php");

// Make this page inaccessible when plugin is not installed. 
/*if (!e107::isInstalled('2fa'))
{
	e107::redirect();
	exit;
}*/

require_once(HEADERF);

$sql = e107::getDb();
$tp  = e107::getParser();


$text = "testing";


// Let's render and show it all!
e107::getRender()->tablerender("2FA", $text);


require_once(FOOTERF);
exit;