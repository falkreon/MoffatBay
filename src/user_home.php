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

try {
	$db = new ReadCapability();
	$user = $db->getUser((int) $_SESSION['user_id']);
	unset($db);

	if ($user === false) {
		// The account associated with the session no longer exists.
		$_SESSION = [];
		session_destroy();
		header('Location: login.php');
		exit;
	}
} catch (Throwable $e) {
	header('Location: login_error.php');
	exit;
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
	<section>
		<h1>Welcome, <?php echo htmlspecialchars($user->FirstName, ENT_QUOTES, 'UTF-8'); ?>!</h1>
		<p>You are logged in to your Moffat Bay Lodge account.</p>
		<p class="user-email"><?php echo htmlspecialchars($user->Email, ENT_QUOTES, 'UTF-8'); ?></p>

		<div class="home-actions">
			<a class="button" href="logout.php">Log Out</a>
			<a class="button callout-button" href="index.php">Moffat Bay Lodge</a>
		</div>
	</section>
</body>
</html>
