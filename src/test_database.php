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

$db = new ReadCapability();
{
	$res = $db->getReservationByConfirmation('MBR-100003');
}
unset($db);

echo($res ? print_r($res) : 'FALSE');

?>
</pre>

<p> Everything is okay.
</body>
