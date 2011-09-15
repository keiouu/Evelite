<?php
/*
 * Evelite Admin App
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
global $home_dir;
include_once($home_dir . "apps/admin/models.php");
require_once($home_dir . "apps/admin/views.php");
require_once($home_dir . "framework/view.php");

// Load views
new AdminIndexView();
?>

