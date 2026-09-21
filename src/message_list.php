<?php declare(strict_types=1);
session_start();

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/21/2026
 */

require_once('database_capability.php');

try {
	$db = new ReadCapability();

	// Are we authorized to view this page?
	if (!$db->sessionHasPermission(Permission::VIEW_OTHER_CONTACT)) {
		header('Location: unauthorized.php');
		exit;
	}

	$resolved = false;
	if (isset($_GET['resolved'])) {
		$resolved = (bool) $_GET['resolved'];
	}

	$searchResults = $db->getContactMessages(includeResolved: $resolved, includeUnresolved: !$resolved);

} catch (Throwable $e) {
	print_r($e);
	//header('Location: generic_error.php');
	//exit;
} finally {
	unset($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Contact messages</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<?php require 'header.php'; ?>
	<div class="center">
	<section>
		<h1>Contact messages</h1>
		<ul class="search-results">
		<?php foreach($searchResults as $result) { ?>
			<a href="view_contact.php?id=<?= $result->Id ?>"><li><?= $result->FullName ?> (<?= $result->Email ?>): <?= $result->Subject ?></li></a>
		<?php } ?>
		</ul>
		<?php if ($resolved) { ?>
			<a class="button" href="message_list.php">Show Unresolved</a>
		<?php } else { ?>
			<a class="button" href="message_list.php?resolved=true">Show Resolved</a>
		<?php } ?>
	</section>
	</div>
</body>
</html>
