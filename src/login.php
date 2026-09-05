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

// If the user is already logged in, send them directly to their home page.
if (isset($_SESSION['user_id'])) {
	header('Location: user_home.php');
	exit;
}

$loginError = isset($_GET['error']) && $_GET['error'] === '1';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Login</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
	<script>
	function updateButton() {
		let valid = true;
		valid &= document.getElementById("email").checkValidity();
		valid &= document.getElementById("password").checkValidity();
		document.getElementById("login").disabled = !valid;
	}
	</script>
</head>
<body>
	<section>
		<h1>Log In</h1>
		<p>(or <a href="register.php">Create an Account</a> instead)</p>

		<?php if ($loginError) { ?>
			<p class="login-error" role="alert">The username or password is incorrect.</p>
		<?php } ?>

		<form class="form-2col" method="POST" action="do_login.php">
			<p><label for="email">Email Address</label>
			   <input type="text" name="email" id="email"
			   pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
			   oninput="updateButton();"
			   autocomplete="email"
			   required>
			</p>

			<p><label for="password">Password</label>
			   <input type="password" name="password" id="password"
			   oninput="updateButton();"
			   autocomplete="current-password"
			   required>
			</p>

			<div class="buttons">
				<a class="button" href="index.html">Cancel</a>
				<input type="submit" value="Log In" id="login" disabled>
			</div>
		</form>
	</section>
</body>
</html>
