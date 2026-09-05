<?php
declare(strict_types=1);  
session_start();

function is_user_logged_in() { 
	return (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']));
			}

?>
<!--
CSD460: Capstone in Software Development
Landing Page for Moffat Bay Lodge
Gold Team
	Isaac Ellingson
	Patrice Moracchini
	Cannon Rivera
	José Velázquez Sáenz
9/6/2026

Landing Page 


-->
<!DOCTYPE html>
<html lang="en">

	<head> 
		<meta charset="utf-8">

		<title>Moffat Bay Lodge</title>

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="base.css">
		<link rel="stylesheet" href="landing.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

	</head>

	<body>
	<a name='top'></a>
	<?php require 'header.php'; ?>	
		<section class="landing">
			<h1>Moffat Bay Lodge</h1>
		</section>

		<section class="content">
			<h2>Welcome to Moffat Bay Lodge</h2>
			<p>	Experience the beauty of nature and the comfort of our lodge.<br>
				Whether you're looking for adventure or relaxation, 
				Moffat Bay Lodge has something for everyone.</p>
			<!-- source: https://pixels.com/featured/lime-kiln-point-state-park-sunset-near-port-angeles-wa-howard-snyder.html -->
			<img src="pictures/lime-kiln-point.jpg" alt="Lime Kiln Point State Park Sunset">
		</section>

		<section class="lodge">
			<h2> Our Lodge</h2>
			<p> We offer everything for a comfortable and quiet stay, and if you prefer to enjoy the scenery and the animal life,<br>
				we provide attractions that will enhance your trip. </p>
			<!--sources:Vrbo. (n.d.). Lopez Legacy Lodge overlooking the beautiful 
			San Juan waters! [Photographs]. Retrieved September 2, 2026, 
			from https://www.vrbo.com/3937680 -->
			<img src="pictures/Lodge_back_view.jpg" alt="Back view of the lodge">

			<?php
			if (is_user_logged_in()) { ?>
				<a href="reservation.php" 
					class="book-your-vacation">Book Your Vacation Today.
				</a>
			<?php } else { ?>
				<a href="login.php" 
					class="book-your-vacation">Create an Account and Book Your Vacation
				</a>
			<?php } ?>
			
		</section>

		<section class="marina">
			<h2> The Marina</h2>
			<p> Enjoy the magical marina with beautiful views<br> and various nautical activities available 
				for our guests. </p>
			<!-- source: San Juan Islands Visitors Bureau. (n.d.). 
			Roche Harbor Marina [Photographs]. Retrieved September 2, 2026, 
			from https://www.visitsanjuans.com/account/roche-harbor-marina -->	
			<img src="pictures/marina.jpeg" alt="Roche Harbor Marina">
			<a href="#"
				class="marina-button">Access The Marina</a>
		</section>

		<section class="attractions">
			<h2> Attractions</h2>
			<p> A variety of attractions are available for our guests,<br>
				including hiking, kayaking, whale watching, and scuba diving.</p>
			<!-- source: https://www.whalewatching.com/where-watch-whales/washington-state/ -->
				<img src="pictures/whale-watching.jpg" alt="Whale Watching">
				<a href="attractions.php"
					class="attractions-button">Explore Attractions</a>
			<a class="back-to-top" href="#top">Back to top</a>

		</section>

	</body>

</html>