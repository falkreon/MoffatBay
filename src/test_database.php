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
require_once('database_capability.php');

$db = new ReadWriteCapability();
{
	$john = $db->getUser(1);
	print_r($john);

	echo('<p>');

	$result = $db->getReservation(1);
	print_r($result);

	echo('<p>');

	$result = $db->getContactMessage(70);
	print_r($result);

	echo('<p>');

	$result = $db->getPermissions($john);
	print_r($result);

	echo('<p>');

	$user = new User();
	$user->Id = NULL;
	$user->Email = "foo@example.com";
	$user->FirstName = "Lord";
	$user->LastName = "Buckethead";
	$user->RoleId = 1;
	print_r($user);

	echo('<p>');

	$result = $db->createUser($user, 'password');

	print_r($result);
	if ($result === FALSE) {
		echo('RESULT WAS FALSE');
	}

	echo('<p>');

	$result = $db->authenticateUser('foo@example.com', 'password');
	if ($result === false) {
		echo('USER WAS NOT AUTHORIZED');
	} else {
		echo('Authorized User:' . PHP_EOL);
		print_r($result);
	}
}
unset($db);

?>
</pre>

<p> Everything is okay.
</body>
