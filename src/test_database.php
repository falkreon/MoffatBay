<!--
CSD 460: Capstone in Software Development
Gold Team
	Isaac Ellingson
	Patrice Moracchini
	Cannon Rivera
	José Velázquez Sáenz

	Test file to show how the database capability can be used.
-->
<html>
<body>

<pre>
<?php
session_start();
require_once('database_capability.php');

$db = new ReadWriteCapability();
{
	$user = $db->getLoggedInUser();
	if ($user === false) {
		echo("No Logged-In User");


	} else {
		$message = ContactMessage::of($user, "Test Subject", "This is a test message. Please disregard.");
		$result = $db->createContactMessage($message);

		print_r($result === FALSE ? 'FALSE' : $result);
	}
}
unset($db);

?>
</pre>

<p> Everything is okay.
</body>
