<?php
/*
 * Evelite Core App
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

include_once(home_dir . "apps/core/models.php");
require_once(home_dir . "apps/core/views.php");
require_once(home_dir . "framework/view.php");

// Load views
new View("/login/", home_dir . "apps/core/templates/login.php");
new View("/register/", home_dir . "apps/core/templates/register.php");
new View("/test/", home_dir . "apps/core/templates/tests.php");
new IndexView();
?>

