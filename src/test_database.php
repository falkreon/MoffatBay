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
	$roomTypes = $db->getRoomTypes();
}
unset($db);

echo(implode(', ', $roomTypes));

?>
</pre>

<p> Everything is okay.
</body>
