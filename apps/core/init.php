<?php
/*
 * Evelite Core App
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
global $home_dir;
include_once($home_dir . "apps/core/models.php");
require_once($home_dir . "apps/core/views.php");
require_once($home_dir . "framework/view.php");

// Load views
new View("/", $home_dir . "apps/core/templates/index.php");
new View("/test/", $home_dir . "apps/core/templates/tests.php");
new IndexView();
?>

