<?php
/*
 * Evelite Core App Index Page
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

$title = "Home Page | Evelite";
include("header.php");
?>

<h1>Welcome to Evelite!</h1>
<ul>
<li><a href="/test/">Run Tests</a></li>
</ul>

<?php
if ($request->get_page_load_time() !== False)
	print "<p>Page loaded in: " . $request->get_page_load_time() . " seconds</p>";
include("footer.php");
?>

