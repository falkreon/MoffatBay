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

// Ensure the user is logged in before accessing the reservation summary page.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Include the database capability for reading and writing reservation data.
require_once('database_capability.php');
$database = new ReadWriteCapability();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:reservation.php');
    exit;
}
    // Retrieve and process the submitted reservation data coming from the form.
    $roomTypeId = (int) $_POST['RoomType'];
    $guestCount = (int)$_POST['guest_count'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $comments = $_POST['comments'];
    // Get the selected room type details from the database.
    $selectedRoomType = $database->getRoomType($roomTypeId);
    // Convert the dates strings to DateTime objects for calculation.
    $checkInDate = new DateTime($checkIn);
    $checkOutDate = new DateTime($checkOut);
    // Calculate the number of nights for the reservation.
    $numberOfNights = $checkOutDate->diff($checkInDate)->days;
    // Calculate the total cost of the reservation.
    $totalCost = $numberOfNights * $selectedRoomType->NightlyRate;
    // Create a new reservation object and populate it with the submitted data.
    $reservation = new Reservation();
    // Assign the submitted data to the reservation object.
     
    $reservation->UserId = (int) $_SESSION['user_id'];
    $reservation->RoomTypeId = $roomTypeId;
    $reservation->GuestCount = $guestCount;
    $reservation->CheckIn = $checkIn;
    $reservation->CheckOut = $checkOut;
    $reservation->SpecialRequests = $comments;
    $reservation->QuotedPrice = $totalCost;

    // Check if the user has confirmed the reservation before creating it in the database.
    if (isset($_POST['confirm_reservation'])) {
        // Generate a unique confirmation number for the reservation.
        $reservation->ConfirmationNumber =  'MBR-' . random_int(100000, 999999);
        // Create the reservation in the database.
        $reservationId = $database->createReservation($reservation);
        // If the reservation was successfully created, redirect to the confirmation page.
        if($reservationId !== false) {
            header("location: reservation_confirmation.php?reservation_id=" . $reservationId);
            exit;
        }

    }
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
		<link rel="stylesheet" href="reservation_summary.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

	<body>
		<a name='top'></a>
		<!-- Include the header for the page -->
		<?php require 'header.php'; ?>

        <section class="section-title">
            <h1>Reservation Summary</h1>
            <p>Review your reservation and confirm.</p>
        </section>

        <section class="reservation-summary">
            <div class="reservation-details">
                <!-- Reservation Information pulled from the d -->
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($selectedRoomType->Name); ?></p>
                <p><strong>Rate per Night:</strong> $<?php echo number_format((float) $selectedRoomType->NightlyRate, 2); ?></p>
                <br>
                <p><strong>Check-in Date:</strong> <?php echo htmlspecialchars($checkIn); ?></p>
                <p><strong>Check-out Date:</strong> <?php echo htmlspecialchars($checkOut); ?></p>
                <br>
                <p><strong>Number of Nights:</strong> <?php echo($numberOfNights); ?></p>
                <p><strong>Number of Guests:</strong> <?php echo($guestCount); ?></p>
                <br>
                <p><strong>Total Cost:</strong> $<?php echo (number_format((float)$totalCost, 2)); ?></p>
                <br>
                <!-- Display special requests if any -->
                <?php if (!empty($comments)) : ?>
                    <p><strong>Comments:</strong> <?php echo htmlspecialchars($comments); ?></p>
                <?php endif; ?>
            </div>
        </section>

        <div class="buttons">
            <a href="index.php" class="cancel-button">Cancel</a>
            <a href="reservation.php" class="edit-button">Edit Reservation</a>
            <!-- Form for confirming the reservation -->
            <form method="post" action="reservation_summary.php" class='confirm-form'>
                <input type="hidden" name="RoomType" value="<?php echo($roomTypeId); ?>">
                <input type="hidden" name="guest_count" value="<?php echo ($guestCount); ?>">
                <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($checkIn); ?>">
                <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($checkOut); ?>">
                <input type="hidden" name="comments" value="<?php echo htmlspecialchars($comments); ?>">
                <button type="submit" name="confirm_reservation" class="confirm-button">Confirm Reservation</button>
            </form>
        </div>
    </body>
</html>