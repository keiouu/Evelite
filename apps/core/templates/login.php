<?php
/*
 * Evelite Core App Login Page
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

$title = "Register | Evelite";
include("includes/header.php");
?>

<h1>Registration</h1>
<ul>
<li><a href="/">Home</a></li>
</ul>

<?php
$showForm = True;
if (isset($_POST['username'])) {
	include(home_dir . "contrib/auth.php");
	$showForm = !User::auth($_POST['username'], $_POST['password'], $_POST['email']);
}
if ($showForm) {
?>

<form method="POST" action="/login/">
	<table>
		<tr><td>Username:</td><td><input type="text" name="username" placeholder="Please type a username" /></td></tr>
		<tr><td>Password:</td><td><input type="password" name="password" /></td></tr>
		<tr><td colspan="2"><input type="submit" /></td></tr>
</form>

<?php
} else {
	print "Logged in..";
}
include("includes/footer.php");
?>

