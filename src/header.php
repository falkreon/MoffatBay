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
	 <img src="Pictures/MoffatBayLogo.png" alt="Moffat Bay Logo" width="153px" height="120px">
</div>

			<nav>
				<a href="#">About</a>
				<a href="#">Attractions</a>
				<a href="#">Book Your Vacation</a>
				<a href="#">Contact Us</a>
			</nav>
			<div class="user">
				<div class="user-left">
					<div class="username">
						<?php
							if (isset($_SESSION['username'])) {
								echo ("Welcome, " . htmlspecialchars($_SESSION['username']));
							} else {
								echo ("Not Logged In");
							}
						?>
					</div>
					<div class="user-links">
						<a href="login.php">Log In</a>
						<a href="registration.php">Sign Up</a>
					</div>
				</div>
				<img src="pictures/user.png" width="64px" height="64px">
			</div>
</header>