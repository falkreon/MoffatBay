<!DOCTYPE html>
<?php
/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/16/2026
 */

session_start(); ?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Register</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<?php require('header.php') ?>
	<div class="center">
		<section>
			<h1>Account Creation Error</h1>
			<p>We're sorry! An error occurred trying to create your account.
			<div class="buttons">
				<a class="button" href="index.php">Cancel</a>
				<a class="button callout-button" href="register.php">Try Again</a>
			</div>
		</section>
	</div>
</body>
</html>
