<?php declare(strict_types=1);
session_start();

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/6/2026
 */

require_once('database_capability.php');

// The user home page is only available to authenticated users.
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

// Figure out whether we're viewing our own home or someone else's
$viewedUser = (isset($_GET['user'])) ?
	(int) $_GET['user'] :
	(int) $_SESSION['user_id'];

try {
	$db = new ReadCapability();

	// Are we authorized to view this page?
	$authorized = ($viewedUser == $_SESSION['user_id']) ||                      // Ownership, or
		$db->hasPermission((int) $_SESSION['user_id'], Permission::VIEW_OTHER_USER); // permission

	if (!$authorized) {
		header('Location: unauthorized.php');
		exit;
	}
	$user = $db->getUser($viewedUser);

} catch (Throwable $e) {
	header('Location: login_error.php');
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
	<title>Moffat Bay Lodge - User Home</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<?php require 'header.php'; ?>
	<div class="center">
	<section>
		<?php
		if ($user === false) {
			?>
			<p>We're sorry, we couldn't find this user account.
			<?php
		} else ?>

			<?php if ($_SESSION['user_id'] == $viewedUser) { ?>
				<h1>Welcome, <?= htmlspecialchars($user->FirstName) ?>!</h1>
				<p>You are logged in to your Moffat Bay Lodge account.</p>
			<?php } else { ?>
				<h1><?= htmlspecialchars($user->FirstName) ?> <?= htmlspecialchars($user->LastName) ?></h1>
			<?php } ?>
			<p class="user-email"><?= htmlspecialchars($user->Email, ENT_QUOTES, 'UTF-8') ?></p>

			<?php if ($_SESSION['user_id'] == $viewedUser) { ?>
			<div class="home-actions">
				<a class="button" href="logout.php">Log Out</a>
				<a class="button callout-button" href="index.php">Moffat Bay Lodge</a>
			</div>
		<?php } ?>
	</section>
	</div>
</body>
</html>
