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
	header('Location: login.php?source=reservation');
		exit;
	}
// Get the available room types from the database.
$roomTypes = $database->getRoomTypes();

// error handling for reservation form submission
$reservationError = isset($_GET['error']) && $_GET['error'] === '1';
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
				
				// Clears the calendar, resets checkin, checkout dates and total cost to avoid
				// displaying incorrect price when changing the room type.
				calendar.clear();
				document.getElementById("checkinDate").value="";
				document.getElementById("checkoutDate").value="";
				document.getElementById("total-cost").innerHTML = "0.00";
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
			<table class="price-rates">
				<tr><th colspan="2"><h2>Price Rate:</h2></th></tr>
				<?php
				foreach ($roomTypes as $roomType) {
					echo "<tr><td>{$roomType->Name}</td><td>{$roomType->NightlyRate}</td></tr>";
				}
				?>
			</table>

	<!-- Reservation form part. Sends reservation detail to reservation_summary.php -->
	<form class="reservation-form" 
				  method="post" 
				  action="reservation_summary.php">

		<section class=	"section-reservation-form">
				
			<!-- Room size dropdown menu gets the available room types and priceratesfrom the database -->
			<div class="room-size">
				<h2>Room size</h2>
				<select name="RoomType" id="roomtype" onchange="onSelectRoomType()">
					<option value="">Select a room size</option>
					<?php
					foreach ($roomTypes as $roomType) {
						echo "<option value=\"{$roomType->Id}\" price-rate =\"{$roomType->NightlyRate}\">{$roomType->Name} - \${$roomType->NightlyRate} per night</option>";
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
				<input type="date" id="checkinDate" name="check_in" readonly>

				<label for="checkoutDate">Check-out Date</label>
				<input type="date" id="checkoutDate" name="check_out" readonly>

				<!-- Display an error message if the reservation form was not filled out correctly -->
				<?php if ($reservationError) { ?>
					<p class="reservation-error" role="alert">The reservation form was not filled out correctly. Please review.</p>
				<?php } ?>
			</div>

			<!-- Calendar for selecting reservation dates. I used Flatpickr instead of CalendarJS
			  because I couldn't make it work correctly with Safari.  -->
			<div class= "calendar" id="calendar"></div>
				<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
				<script>
					// create an instance of flatpickr. (use const because we won't need
					// to reassign the constant calendar to something else.)
					const calendar = flatpickr(".calendar", {
						mode: "range",
						minDate: "today",
						inline: true,
						appendTo:  document.getElementById("calendar"),
						dateFormat: "Y-m-d",
						onChange: function(selectedDates, dateStr, instance) {
						// If only one date is selected, this date is set to checkin, 
						// checkout date is cleared to avoid incorrect values displayed on cost calculation,
						//and the total cost is reset.
						if (selectedDates.length===1) {
							document.getElementById("checkinDate").value = instance.formatDate(selectedDates[0], "Y-m-d");
							
							document.getElementById("checkoutDate").value = "";
							document.getElementById("total-cost"). innerHTML = "0.00";
							}

						// Sets the first date as check in and the second date as check out,
						// making it impossible to have check-out befor check-in.
						// If two dates are selected, set the check-in and check-out dates and calculate the total cost.
						if (selectedDates.length===2) {
							document.getElementById("checkinDate").value = instance.formatDate(selectedDates[0], "Y-m-d");
							document.getElementById("checkoutDate").value = instance.formatDate(selectedDates[1], "Y-m-d");
						// the calculation requires to divide the date difference by the number of milliseconds in a day,
						// as JS stores dates in milliseconds.
							const nights = (selectedDates[1]- selectedDates[0]) / 86400000;
							
							calculateTotalCost(nights);
							}
					}});
				</script>

			<!-- total cost display -->
			<div class="total-cost">
				<p>Total Cost: $<span id="total-cost">0.00</span></p>
				<script>
					// Function that calculates the total cost 
					// based on the number of nights and selected room type.
					function calculateTotalCost(nights) {
						const roomSelect = document.getElementById("roomtype");
						const selectedRoom =roomSelect.options[roomSelect.selectedIndex];
						const price = selectedRoom.getAttribute("price-rate");
	
						const totalCost = price * nights;
						// modify the total cost displayed with two decimal places,
						// according to the reservation.
						document.getElementById("total-cost").innerHTML = totalCost.toFixed(2);
						return totalCost;
					}
				</script>
			</div>

			<!-- Comments section for special requests -->
			<div class="comments">
				<h2>Special requests or comments</h2>
				<textarea name="comments"></textarea>
			</div>

			
		</section>
		<!-- Reservation submit button -->
		 <div class="reservation-button-container">
		<button class="reservation-button" type="submit">Continue</button>
		</div>

	</form>
		
	</body>
</html>
