<?php
declare(strict_types=1);  

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/6/2026
 */
?>
<header class="header">
	<div class="logo">
		<!-- source;"https://pngtree.com/freepng/lighthouse-beach-logo-vector_3642417.html/" modified with Photoshop.-->
		<a href="index.php">
		<img src="pictures/MoffatBayLogo.png" alt="Moffat Bay Logo" width="153px" height="120px">
		</a>
	</div>

	<nav>
		<a href="about.php">About</a>
		<a href="attractions.php">Attractions</a>
		<a href="reservation.php">Book Your Vacation</a>
		<a href="contact.php">Contact Us</a>
	</nav>

	<div class="user">
		<?php
		$user = false;

		if (isset($_SESSION['user_id'])) {
			require_once('database_capability.php');
			$db = new ReadCapability();
			$user = $db->getUser((int) $_SESSION['user_id']);
			unset($db);

			if ($user !== false) {
		?>
			<div class="user-left">
				<div class="username"><?= $user->FirstName . ' ' . $user->LastName ?></div>

				<div class="user-links">
					<a href="logout.php">Log Out</a>
					<a href="user_home.php">Profile</a>
				</div>
			</div>
			<img src="pictures/user.png" width="64px" height="64px">
		<?php
			}
		}

		if ($user === false) {
		?>
			<div class="user-left">
				<div class="username">Not Logged In</div>

				<div class="user-links">
					<a href="login.php">Log In</a>
					<a href="register.php">Sign Up</a>
				</div>
			</div>
			<img src="pictures/user.png" width="64px" height="64px">
		<?php
		}
		?>
	</div>
</header>
