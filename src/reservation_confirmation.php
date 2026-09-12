<?php
declare(strict_types=1);

/**
 * CSD460: Capstone in Software Development
 * Moffat Bay Lodge
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/12/2026
 */

session_start();
// Ensure the user is logged in before accessing the reservation summary page.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once('database_capability.php');

$database = new ReadCapability();


$reservationId = (int) $_GET['reservation_id'];
$reservation = $database->getReservation($reservationId);

if ($reservation === false) {
    // Handle the case where the reservation was not found.
    header('Location: reservation.php');
    exit;
}
// Get the room type details for the reservation.
$roomType = $database->getRoomType((int) $reservation->RoomTypeId);
//
$checkInDate = new DateTime($reservation->CheckIn);
$checkOutDate = new DateTime($reservation->CheckOut);
// Calculate the number of nights for the reservation.
$numberOfNights = $checkOutDate->diff($checkInDate)->days;
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
		<link rel="stylesheet" href="reservation_confirmation.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

	<body>
		<a name='top'></a>
		<!-- Include the header for the page -->
		<?php require 'header.php'; ?>
        <section class="section-title">
            <h1>Reservation Confirmation</h1>
        </section>

        <section class="confirmation-message">
            <p>Thank you for choosing Moffat Bay Lodge.</p>
            <p>We are looking forward to making your stay an unforgettable experience.</p>
        </section>

        <section class="reservation-summary">
            <div class="reservation-details">
                <h3>Reservation Information</h3>
                
                <p><strong>Confirmation Number:</strong> <?php echo htmlspecialchars($reservation->ConfirmationNumber); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($roomType->Name); ?></p>
                <p><strong>Rate per Night:</strong> $<?php echo number_format((float) $roomType->NightlyRate, 2); ?></p>
                
                <p><strong>Check-in Date:</strong> <?php echo htmlspecialchars($reservation->CheckIn); ?></p>
                <p><strong>Check-out Date:</strong> <?php echo htmlspecialchars($reservation->CheckOut); ?></p>
                
                <p><strong>Number of Nights:</strong> <?php echo($numberOfNights); ?></p>
                <p><strong>Number of Guests:</strong> <?php echo($reservation->GuestCount); ?></p>
                
                <p><strong>Total Cost:</strong> $<?php echo (number_format((float)$reservation->QuotedPrice, 2)); ?></p>
                
                <?php if (!empty($reservation->SpecialRequests)) : ?>
                    <p><strong>Comments:</strong> <?php echo htmlspecialchars($reservation->SpecialRequests); ?></p>
                <?php endif; ?>
            </div>

            <div class="lookup-button">
                <a href="lookup_reservation.php">Look up Reservation</a>
            </div>
        </section>

    </body>
</html>