<?php declare(strict_types=1);
session_start();

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/16/2026
 */

require_once('database_capability.php');

// The user search page is only available to authenticated users.
if (!isset($_SESSION['user_id'])) {
	header('Location: unauthorized.php');
	exit;
}

try {
	$db = new ReadCapability();

	// Are we authorized to view this page?
	// VIEW_OTHER_USER is required *regardless* of who we're searching for
	$authorized = $db->hasPermission((int) $_SESSION['user_id'], Permission::VIEW_OTHER_USER);

	if (!$authorized) {
		header('Location: unauthorized.php');
		exit;
	}

} catch (Throwable $e) {
	header('Location: generic_error.php');
	exit;
} finally {
	unset($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - User Search</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<?php require 'header.php'; ?>
	<div class="center">
	<section>
		<h1>User Search</h1>
		<form method="POST" action="do_find_user.php" class="search-form">
			<input type="search" name="search" id="search" required>
			<input type="submit" class="callout-button" value="Search">
		</form>
	</section>
	</div>
</body>
</html>
