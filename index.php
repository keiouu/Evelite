<?php
/*
 * Evelite
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

$time = microtime(True);

ini_set('display_errors', '1');
define("home_dir", dirname(__FILE__) . '/');

require_once(home_dir . "config.php");
require_once(home_dir . "framework/view_manager.php");
require_once(home_dir . "framework/app_loader.php");
require_once(home_dir . "framework/request.php");
require_once(home_dir . "contrib/timer.php");

$view_manager = new ViewManager();
load_applications();
$request = new Request(Timer::startAt($time));

header('Server: Unknown');
header('X-Powered-By: Seemingly Nothing');
header('Content-type: ' . $request->mimeType);

$view_manager->get($request->page)->render($request);

?>

