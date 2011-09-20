<?php
/*
 * Evelite Core App Registration Page
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
if (isset($_POST['username']) && $_POST['password'] == $_POST['password2']) {
	include(home_dir . "contrib/auth.php");
	try {
		User::create_user($_POST['username'], $_POST['password'], $_POST['email']);
		$showForm = False;
		print "<h3>Thankyou..</h3>";
	}
	catch (AuthException $e) {
		print "<h2>Error, please try again. It is likely that username exists.</h2>";
	}
}
if ($showForm) {
?>

<form method="POST" action="/register/">
	<table>
		<tr><td>Username:</td><td><input type="text" name="username" placeholder="Please type a username" /></td></tr>
		<tr><td>Password:</td><td><input type="password" name="password" /></td></tr>
		<tr><td>Password (again):</td><td><input type="password" name="password2" /></td></tr>
		<tr><td>Email Address:</td><td><input type="email" name="email" /></td></tr>
		<tr><td colspan="2"><input type="submit" /></td></tr>
</form>

<?php
}
include("includes/footer.php");
?>

