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
 * 10/11/2026
 */

session_start();
// Ensure the user is logged in before accessing the page.
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

require_once('database_capability.php');
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
		<?php require 'header.php'; ?>
		
		<section class="reservation-summary">

<?php

// If this is a GET request and it has a confirmation number attached, show the delete confirmation

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (!isset($_GET['r'])) {
		header('Location: generic_error.php');
		exit;
	}
	$conf = $_GET['r'];
	
	$db = new ReadCapability();
	$res = $db->getReservationByConfirmation($conf);
	if ($res === false) {
		header('Location: generic_error.php');
		exit;
	}
	
	// More permission checks.
	if (($_SESSION['user_id'] != (int) $res->UserId) && !$db->sessionHasPermission(Permission::EDIT_OTHER_RESERVATION)) {
		header('Location: unauthorized.php');
		exit;
	}
	
	$roomType = $db->getRoomType((int) $res->RoomTypeId);
	$checkIn = new DateTime($res->CheckIn);
	$checkOut = new DateTime($res->CheckOut);
	$numberOfNights = $checkOut->diff($checkIn)->days;
	?>
	<form class="reservation-details" action="cancel_reservation.php" method="POST">
		<input type="hidden" name="r" value="<?= htmlspecialchars($_GET['r']) ?>">
		
		<h1>Are you sure you want to cancel this reservation?</h1>
		<p>Cancellation will be permanent. It cannot be undone!
		
		<h3>Reservation Information</h3>

		<p><strong>Confirmation Number:</strong> <?= htmlspecialchars($res->ConfirmationNumber); ?></p>
		<p><strong>Room Type:</strong> <?= htmlspecialchars($roomType->Name); ?></p>
		<p><strong>Rate per Night:</strong> $<?= number_format((float) $roomType->NightlyRate, 2); ?></p>

		<p><strong>Check-in Date:</strong> <?= htmlspecialchars($res->CheckIn); ?></p>
		<p><strong>Check-out Date:</strong> <?= htmlspecialchars($res->CheckOut); ?></p>

		<p><strong>Number of Nights:</strong> <?= (int) $numberOfNights ?></p>
		<p><strong>Number of Guests:</strong> <?= (int) $res->GuestCount ?></p>

		<p><strong>Total Cost:</strong> $<?= number_format((float) $res->QuotedPrice, 2) ?></p>

		<?php if (!empty($res->SpecialRequests)) : ?>
		    <p><strong>Comments:</strong> <?= nl2br(htmlspecialchars($res->SpecialRequests)) ?></p>
		<?php endif; ?>
		<div class="buttons">
			<a href="user_home.php" class="button">Go Back</a>
			<input type="submit" class="callout-button" value="Cancel Reservation">
		</div>
	</form>
	
	<?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!isset($_POST['r'])) {
		header('Location: generic_error.php');
		exit;
	}
	$conf = $_POST['r'];
	
	$db = new ReadWriteCapability();
	$res = $db->getReservationByConfirmation($conf);
	if ($res === false) {
		header('Location: generic_error.php');
		exit;
	}
	
	// More permission checks.
	if (($_SESSION['user_id'] != (int) $res->UserId) && !$db->sessionHasPermission(Permission::EDIT_OTHER_RESERVATION)) {
		header('Location: unauthorized.php');
		exit;
	}
	
	
	
	$result = $db->deleteReservation($res);
	
	if ($result === false) {
		?>
		<div class="reservation-details">
			<h1>A Problem Occurred.</h1>
			
			<p>Something went wrong cancelling the reservation.</p>
			
			<div class="buttons">
				<a class="button" href="user_home.php">Home</a>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="reservation-details">
			<h1>Reservation Cancelled.</h1>
			
			<p>We're sorry you had to cancel! Please come back soon.</p>
			<div class="buttons">
				<a class="button" href="user_home.php">Home</a>
			</div>
		</div>
		<?php
	}
}
?>

	</section>
</body>
</html>
