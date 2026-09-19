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
	// RBAC - we're performing privileged operations, so once we pass this fence, get-by-id is permitted
	if (!$db->sessionHasPermission(Permission::EDIT_OTHER_RESERVATION)) {
		header('Location: unauthorized.php');
		exit;
	}

	if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
		header('Location: generic_error.php');
		exit;
	}

	// Also implicitly checks !isset()
	if (empty($_GET['id'])) {
		header('Location: generic_error.php');
		exit;
	}

	$res = $db->getReservation((int) $_GET['id']);
	if ($res === false) {
		// Super weird to arrive here. If we do, something's broken.
		header('Location: generic_error.php');
		exit;
	}

	// We need *all* roomTypes to populate the dropdown
	$roomTypes = $db->getRoomTypes();
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
		<?php require 'header.php'; ?>

		<section class="section-title">
			<h1>Edit Reservation <?= $res->ConfirmationNumber ?></h1>
		</section>

		<section class="reservation-summary">
			<div class="reservation-details">
				<form class="form-2col" method="POST" action="do_edit_reservation.php">
					<input type="hidden" name="id" value="<?= $res->Id ?>">

					<label for="roomType">Room Type</label>
					<select id="roomType" name="roomType" required>
						<?php foreach($roomTypes as $i => $roomType) { ?>
							<?php if ($roomType->Id == $res->RoomTypeId) { ?>
								<option value="<?= $roomType->Id ?>" selected><?= $roomType->Name ?></option>
							<?php } else { ?>
								<option value="<?= $roomType->Id ?>"><?= $roomType->Name ?></option>
							<?php } ?>
						<?php } ?>
					</select>

					<label for="checkIn">Check In</label>
					<input type="date" id="checkIn" name="checkIn" value="<?= $res->CheckIn ?>" required>

					<label for="cehckOut">Check Out</label>
					<input type="date" id="checkOut" name="checkOut" value="<?= $res->CheckOut ?>" required>

					<label for="guestCount">Guest Count</label>
					<input type="number" id="guestCount" name="guestCount" min="0" max="6" value="<?= $res->GuestCount ?>" required>

					<label for="quotedPrice">Total Cost</label>
					<input type="number" id="quotedPrice" name="quotedPrice" min="0" step="0.01" value="<?= $res->QuotedPrice ?>" required>

					<label for="specialRequests">Special Requests</label>
					<textarea id="specialRequests" name="specialRequests"><?= htmlspecialchars($res->SpecialRequests) ?></textarea>
					<div class="buttons">
						<a href="view_reservation.php?r=<?= $res->ConfirmationNumber ?>" class="cancel-button">Cancel</a>
						<input type="submit" class="confirm-button confirm-form" value="Save">
					</buttons>
				</form>
			</div>
		</section>
	</body>
</html>

