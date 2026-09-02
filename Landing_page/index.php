<?php
declare(strict_types=1);  
	session_start();
?>
<!--
CSD460: Capstone in Software Development
Landing Page for Moffat Bay Lodge
Gold Team
	Isaac Ellingson
	Patrice Moracchini
	Cannon Rivera
	José Velázquez Sáenz


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
	<?php require_once 'header.php'; ?>	
		<section class="landing" id="top">
			<h1>Moffat Bay Lodge</h1>

			<?php
			function is_user_logged_in() { 
				return (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']));
			}

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

		<section class="content">
			<h2>Welcome to Moffat Bay Lodge</h2>
			<p>	Experience the beauty of nature and the comfort of our lodge. 
				Whether you're looking for adventure or relaxation, 
				Moffat Bay Lodge has something for everyone.</p>
		</section>

		<section class="lodge">
			<div class="lodge-back">
			<h2> Our Lodge</h2>
			<p> We offer everything for a comfortable and quiet stay, and if you prefer to enjoy the scenery and the animal life,
				we provide attractions that will enhance your trip. </p>
			</div>
			<div class="lodge-chairs"></div>
		</section>

		<section class="rooms">
			
			<div class="scroll-container">
				<!-- sources:Vrbo. (n.d.). Lopez Legacy Lodge overlooking the beautiful 
				San Juan waters! [Photographs]. Retrieved September 2, 2026, 
				from https://www.vrbo.com/3937680 -->
  				<img src="Pictures/double_full_bed_room.jpeg" alt="Double Full Bed Room" width="600" height="400">
  				<img src="Pictures/king_bed_room.jpg" alt="King Bed Room" width="600" height="400">
  				<img src="Pictures/queen_bed_room.jpeg" alt="Queen Bed Room" width="600" height="400">
				<img src="Pictures/double_queen_bed_room.jpg" alt="Double Queen Bed Room" width="600" height="400">
			</div>
				<h2> Our Rooms</h2>
			<p> We provide four types of rooms with sea views and forest views, each will bring you a different ambiance and will fulfill your needs.
				All of our rooms are equipped with modern amenities.</p>
			
		</section>

		<section class="marina">
			<h2> The Marina</h2>
			<p> Enjoy the magical marina with beautiful views<br> and various nautical activities available 
				for our guests. </p>
			
		</section>

		<section class="attractions">
			<h2> Attractions</h2>
			<p> A variety of attractions are available for our guests,
				including hiking, kayaking, whale watching, and scuba diving.</p>
			<a class="back-to-top" href="#top">Back to top</a>	
		</section>

	</body>

</html>