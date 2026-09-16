<?php declare(strict_types=1);

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/16/2026
 */

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Login Error</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<?php require('header.php') ?>
	<div class="center">
		<section>
			<h1>An Error Occurred</h1>
			<p>We're sorry. An error occurred while trying to perform that action.</p>
			<div class="buttons centered-buttons">
				<a class="button callout-button" href="index.php">Home</a>
			</div>
		</section>
	</div>
</body>
</html>
