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
		<link rel="stylesheet" href="about_us.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style></style>
	</head>

	<body>
	<a name='top'></a>
	<?php require 'header.php'; ?>
	<div>
		<h1 class="title">About Us</h1>
		<h3 class="subtitle">Where Adventure Meets Relaxation</h3>
		<p class="head">
			Moffat Bay Lodge is your island escape for peaceful views, outdoor adventure, 
			and time well spent. Whether you're planning a romantic getaway,
 			a family trip, or a relaxing retreat, you'll find comfort and adventure all in one place.
		</p>
	</div>
	<section class="moffat-section">

    <div class="moffat-image">
			<img src="pictures/Lodge_back_view.jpg" alt="Back view of the lodge">
    </div>

    <div class="moffat-text">
        <h2>A Getaway for Every Kind of Traveler</h2>

        <p>
            No two vacations look the same. Some guests come to explore every trail
            and stretch of coastline, while others prefer quiet mornings, beautiful
            views, and time spent with the people who matter most.
        </p>

        <p>
            Moffat Bay Lodge offers the perfect blend of adventure, comfort, and unforgettable scenery.
    		Wake up to peaceful waterfront views, spend the day exploring the island, and unwind in a
    		warm, welcoming lodge surrounded by nature. Whether you're seeking excitement or a quiet
    		escape, every stay is designed to feel memorable.
        </p>
    </div>

	</section>
	<section class="attraction-section">
	<div class="attraction-text">
        <h2>Unforgettable Attractions</h2>

        <p>
    		Adventure is everywhere at Moffat Bay Lodge. Paddle through crystal-clear coastal waters,
    		hike scenic trails with breathtaking island views, spot majestic whales in their natural
    		habitat, and dive beneath the surface to explore an incredible underwater world. With so
    		many unforgettable experiences just moments away, every day brings a new adventure.
		</p>
    </div>
    <div class="attraction-image">
			<img src="pictures/scuba.jpg" alt="Back view of the lodge">
    </div>

    

	</section>
	</body>

</html>
