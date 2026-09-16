<?php
declare(strict_types=1);
session_start();

/* CSD460: Capstone in Software Development
 * Moffat Bay Lodge
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/6/2026
 */
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">

		<title>Moffat Bay Lodge</title>

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="base.css">
		<link rel="stylesheet" href="attractions.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

	</head>

	<body>
	<a name='top'></a>
	<?php require 'header.php'; ?>
		<h1>Attractions</h1>
		<section>
			<img src="pictures/hiking.jpg" alt="Someone Hiking">

			<div class="text">
				<h2>Hiking</h2>

				<p>
					Lace up your hiking boots and get ready to explore the beauty surrounding Moffat Bay! 
					From peaceful forest trails to breathtaking coastal views, there’s always something new waiting around the next bend.
					Whether you’re looking for a relaxing walk through nature or an exciting outdoor adventure, 
					hiking is the perfect way to take in the fresh air, stunning scenery, and unforgettable sights of the island. 
					Grab your camera, bring a friend, and see where the trail takes you!
				</p>
			</div>
		</section>
		<section>
			<img src="pictures/kayaking.jpg" alt="2 people kayaking">

			<div class="text">
				<h2>Kayaking</h2>

				<p>
					Grab a paddle and experience Moffat Bay from the water! 
					Kayaking is a fun and relaxing way to explore the shoreline, 
					take in the beautiful scenery, and enjoy the peaceful sounds of nature all around you. 
					Whether you're gliding across calm waters, spotting local wildlife, or simply enjoying the fresh air, 
					every trip offers a new adventure. Perfect for beginners and experienced paddlers alike, 
					kayaking is a great way to make unforgettable memories during your stay.
				</p>
			</div>
		</section>
		<section>
			<img src="pictures/whale-watching.jpg" alt="People whale watching">

			<div class="text">
				<h2>Whale Watching</h2>

				<p>
					Set your sights on the open water and get ready for an unforgettable whale watching experience! 
					There’s nothing quite like seeing these incredible creatures up close as they swim, splash, 
					and move through the bay. Along the way, you can enjoy the fresh ocean air, 
					beautiful coastal views, and maybe even spot other marine wildlife. 
					Whether it’s your first time whale watching or one of many adventures, 
					it’s an experience you won’t soon forget.
				</p>
			</div>
		</section>
		<section>
			<img src="pictures/scuba.jpg" alt="Someone scuba diving">

			<div class="text">
				<h2>Scuba Diving</h2>

				<p>
					Dive beneath the surface and discover a whole new side of Moffat Bay! 
					Scuba diving gives you the chance to explore the underwater world, 
					from colorful marine life to hidden sights beneath the waves. 
					Whether you’re an experienced diver or looking to try something new, 
					the clear waters and peaceful surroundings make every dive an exciting adventure. 
					Take the plunge and create memories that go far beyond the shoreline.
				</p>
			</div>
		</section>
	</body>

</html>

