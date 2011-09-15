<?php
/*
 * Evelite Admin App Header
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
$media_url = $GLOBALS["media_url"];
?>

<!doctype html> 
<html lang="en">
<head> 
  <meta charset="utf-8">
 
  <title><?php print $title; ?> | Evelite Admin</title> 
  <meta name="description" content="Evelite"> 
  <meta name="author" content="Evelite">
  
  <link rel="stylesheet" type="text/css" href="<?php print $media_url; ?>media/themes/default/theme.css" media="screen" /> 
  <link rel="stylesheet" type="text/css" href="<?php print $media_url; ?>apps/admin/media/themes/default/style.css" media="screen" />
  
  <script src="<?php print $media_url; ?>media/jQuery/jquery-1.6.4.min.js" type="text/javascript"></script>
  <script src="<?php print $media_url; ?>media/jQuery/ui/jquery-ui-1.8.16.min.js" type="text/javascript"></script>

</head> 
 
<body>
<div id="userbar" class="ui-widget ui-widget-header"></div>
  
