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
 * 9/18/2026
 */


require_once('database_capability.php');
$db = new ReadWriteCapability();
try {
	if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
		// You can't get here by normal means - user is tampering
		header('Location: generic_error.php');
		exit;
	}
	// roomType validation
	if (!isset($_GET['r']) || empty($_GET['r'])) {
		// You shouldn't get here by normal means - user is probably altering the address bar
		header('Location: generic_error.php');
		exit;
	}

	$res = $db->getReservationByConfirmation($_GET['r']);
	if ($res === false) {
		// You shouldn't get here by normal means - this is either something of ours being broken,
		// a race condition where a reservation was deleted, or the user trying to guess MBR numbers.
		header('Location: generic_error.php');
		exit;
	} else {
		$roomType = $db->getRoomType((int) $res->RoomTypeId);
		if ($roomType === false) {
			// Even weirder!
			header('Location: generic_error.php');
			exit;
		}
	}
	$checkOut = new DateTime($res->CheckOut);
	$checkIn = new DateTime($res->CheckIn);
	$numberOfNights = $checkOut->diff($checkIn)->days;
} finally {
	unset($db);
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
			<h1>Reservation <?= $res->ConfirmationNumber ?></h1>
		</section>

		<section class="reservation-summary">
			<div class="reservation-details">
				<!-- Reservation Information pulled from the d -->
				<p><strong>Room Type:</strong> <?php echo htmlspecialchars($roomType->Name); ?></p>
				<p><strong>Rate per Night:</strong> $<?php echo number_format((float) $roomType->NightlyRate, 2); ?></p>
				<br>
				<p><strong>Check-in Date:</strong> <?php echo htmlspecialchars($res->CheckIn); ?></p>
				<p><strong>Check-out Date:</strong> <?php echo htmlspecialchars($res->CheckOut); ?></p>
				<br>
				<p><strong>Number of Nights:</strong> <?php echo($numberOfNights); ?></p>
				<p><strong>Number of Guests:</strong> <?php echo($res->GuestCount); ?></p>
				<br>
				<p><strong>Total Cost:</strong> $<?php echo(number_format((float)$res->QuotedPrice, 2)); ?></p>
				<br>
				<!-- Display special requests if any, htmlspecialchars is used to prevent XSS -->
				<?php if (!empty($res->SpecialRequests)) : ?>
					<p><strong>Comments:</strong> <?php echo htmlspecialchars($res->SpecialRequests); ?></p>
				<?php endif; ?>
			</div>
		</section>
	</body>
</html>
