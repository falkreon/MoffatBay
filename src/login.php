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

const VALID_SOURCES = [ 'reservation' ];
if (isset($_GET['source'])) {
	if (in_array($_GET['source'], VALID_SOURCES)) $source = $_GET['source'];
}
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

	function togglePasswordVisibility(inputId, button) {
		const passwordInput = document.getElementById(inputId);
		const showPassword = passwordInput.type === "password";
		const buttonLabel = showPassword ? "Hide password" : "Show password";

		passwordInput.type = showPassword ? "text" : "password";
		button.setAttribute("aria-pressed", showPassword ? "true" : "false");
		button.setAttribute("aria-label", buttonLabel);
		button.setAttribute("title", buttonLabel);
	}
	</script>
</head>
<body>
	<?php require 'header.php'; ?>
	<div class="center">
	<section>
		<h1>Log In</h1>
		<?php if (isset($source)) { ?>
			<p>(or <a href="register.php?source=<?= $source ?>">Create an Account</a> instead)
		<?php } else { ?>
			<p>(or <a href="register.php">Create an Account</a> instead)
		<?php } ?>

		<?php if ($loginError) { ?>
			<p class="login-error" role="alert">The username or password is incorrect.</p>
		<?php } else if (isset($source)) { ?>
			<p class="login-error" role="alert">You need to login to do that.</p>
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
				<span class="password-field">
					<input type="password" name="password" id="password"
					oninput="updateButton();"
					autocomplete="current-password"
					required>
					<button type="button" class="password-toggle"
						onclick="togglePasswordVisibility('password', this)"
						aria-controls="password" aria-label="Show password"
						aria-pressed="false" title="Show password">
						<!--
						Eye and Eye Off icons from Feather Icons.
						https://feathericons.com/
						MIT License.
						-->
						<svg class="password-icon password-icon-show"
							viewBox="0 0 24 24"
							aria-hidden="true">
							<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
							<circle cx="12" cy="12" r="3"></circle>
						</svg>

						<svg class="password-icon password-icon-hide"
							viewBox="0 0 24 24"
							aria-hidden="true">
							<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
							<line x1="1" y1="1" x2="23" y2="23"></line>
						</svg>
					</button>
				</span>
			</p>

			<div class="buttons">
				<a class="button" href="index.php">Cancel</a>
				<input type="submit" class="callout-button" value="Log In" id="login" disabled>
			</div>
			<?php if (isset($source)) { ?>
				<input type="hidden" name="source" value="<?= $source ?>">
			<?php } ?>
		</form>
	</section>
	</div>
</body>
</html>
