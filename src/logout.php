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

// First things first: If we came here, we want to auto-logout from the previous session
$_SESSION = []; // Destroy server-side session data
// Destroy the session cookie -
// See: https://www.php.net/manual/en/function.session-destroy.php
if (ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
	);
}
// Finally, destroy the session itself. do_register or do_login will build a new session for us.
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Register</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
	<section>
		<h1>You have been logged out</h1>
		<p>Please wait, you will be redirected. If you are stuck, please <a href="index.php">click here to continue</a>.
		<script>
			window.location.replace("index.php");
		</script>
	</section>
</body>
</html>
