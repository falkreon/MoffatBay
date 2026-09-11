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
 * 9/11/2026
 */

require_once('database_capability.php');
$database = new ReadCapability();
// Get the currently logged-in user from the database.
$loggedInUser = $database->getLoggedInUser();
// display an error page if a guest is not logged in.
if ($loggedInUser === false) {
	header('Location: ReservationError.php?err=login-needed');
		exit;
	}
// Get the available room types from the database.
$roomTypes = $database->getRoomTypes();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">

		<title>Moffat Bay Lodge</title>
		
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<link rel="stylesheet" href="base.css">
		<link rel="stylesheet" href="reservation.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script>
            const ROOM_OCCUPANCY = [
            <?php
                foreach ($roomTypes as $roomType) {
                    echo $roomType->MaxGuests . ","; // Trailing commas are allowed in js
                }
            ?>
            ];

            function onSelectRoomType() {
                let index = document.getElementById("roomtype").selectedIndex - 1;
                let guestsDropdown = document.getElementById("guest_count");
                if (index < 0) {
                    guestsDropdown.replaceChildren();
                    guestsDropdown.disabled = true;
                } else {
                    guestsDropdown.replaceChildren();
                    let maxGuests = (index < ROOM_OCCUPANCY.length) ? ROOM_OCCUPANCY[index] : 6;
                    for(let i = 0; i<maxGuests; i++) {
                        // https://caniuse.com/mdn-api_htmlselectelement_add - baseline support
                        guestsDropdown.add(new Option((i+1) + ' guests'));
                    }

                    guestsDropdown.disabled = false;
                }

            }
        </script>


	</head>

	<body>
		<a name='top'></a>
		<!-- Include the header for the page -->
		<?php require 'header.php'; ?>


		<section class="section-title">
			<h1>Create Room Reservation</h1>
			<p> Select your room, dates and number of guests.</p>
		</section>

		<section class="reservation">

			<!-- Display the price rates for different room types -->
			<div class="price-rates">
				<h2>Price Rate:</h2>
				 <p>Double full beds: $126.00 per night</p>
				 <p>Queen : $141.75 per night</p>
				 <p>Double queen beds: $157.50 per night</p>
				 <p>King : $168.00 per night</p>
			</div>

	<!-- Reservation form part. Sends reservation detail to reservation_summary.php -->
	<form class="reservation-form" 
				  method="post" 
				  action="reservation_summary.php">

		<section class="section-reservation-form">
				
			<!-- Room size dropdown menu gets the available room types from the database -->
			<div class="room-size">
				<h2>Room size</h2>
				<select name="RoomType" id="roomtype" onchange="onSelectRoomType()" required>
					<option value="">Select a room size</option>
					<?php
					foreach ($roomTypes as $roomType) {
						echo "<option value=\"{$roomType->Id}\">{$roomType->Name}</option>";
					}
					?>
				</select>
			</div>

			<!-- Number of guests dropdown menu -->
			<div class ="guests">
				<h2>Number of guests</h2>
					<select name="guest_count" id="guest_count" disabled>
					</select>
			</div>	

			<!-- Check-in and check-out dates boxes -->
			<div class="reservation-dates">
				<label for="checkinDate">Check-in Date</label>
				<input type="date" id="checkinDate" name="check_in" required>

				<label for="checkoutDate">Check-out Date</label>
				<input type="date" id="checkoutDate" name="check_out" required>
			</div>

			<!-- Calendar for selecting reservation dates. I used Flatpickr instead of CalendarJS
			  because I couldn't make it work correctly with Safari.  -->
			<div class= "calendar" id="calendar"></div>
				<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
				<script>
					flatpickr(".calendar", {
						mode: "range",
						minDate: "today",
						inline: true,
						appendTo:  document.getElementById("calendar"),
						dateFormat: "Y-m-d",
						onChange: function(selectedDates, dateStr, instance) {
						if (selectedDates.length===1) {
							document.getElementById("checkinDate").value = instance.formatDate(selectedDates[0], "Y-m-d");
							}
							// makes it impossible to have the check-out date before the check-in date.
						if (selectedDates.length===2) {
							document.getElementById("checkinDate").value = instance.formatDate(selectedDates[0], "Y-m-d");
							document.getElementById("checkoutDate").value = instance.formatDate(selectedDates[1], "Y-m-d");
							}
							}
					});
				</script>

			<!-- Comments section for special requests -->
			<div class="comments">
				<h2>Special requests or comments</h2>
				<textarea name="comments"></textarea>
			</div>
		</section>

		<!-- Reservation submit button -->
		<button class="reservation-button" type="submit">Book Your Reservation</button>
	</form>
		
	</body>
</html>
