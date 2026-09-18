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
 * 9/18/2026
 *
 * Public reservation lookup by customer email address or MBR confirmation number.
 */

session_start();
require_once('database_capability.php');

$searchMethod = '';
$email = '';
$confirmationNumber = '';
$errorMessage = '';
$reservations = [];
$roomTypes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$searchMethod = $_POST['search_method'] ?? '';

	if ($searchMethod === 'email') {
		$email = trim($_POST['email'] ?? '');

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			$errorMessage = 'Please enter a valid email address and try again.';
		}
	} elseif ($searchMethod === 'confirmation') {
		$confirmationNumber = strtoupper(trim($_POST['confirmation_number'] ?? ''));

		if (preg_match('/^MBR-[0-9]{6}$/', $confirmationNumber) !== 1) {
			$errorMessage = 'Please enter a valid MBR number in the format MBR-123456 and try again.';
		}
	} else {
		$errorMessage = 'Please choose a reservation lookup method and try again.';
	}

	if ($errorMessage === '') {
		try {
			$database = new ReadCapability();

			if ($searchMethod === 'email') {
				$reservations = $database->getReservationsByEmail($email);
			} else {
				$reservation = $database->getReservationByConfirmation($confirmationNumber);
				$reservations = $reservation === false ? [] : [$reservation];
			}

			foreach ($reservations as $reservation) {
				$roomTypes[(int) $reservation->RoomTypeId] =
					$database->getRoomType((int) $reservation->RoomTypeId);
			}

			unset($database);

			if (count($reservations) === 0) {
				$errorMessage = 'We could not find a reservation with that information. Please check your entry and try again.';
			}
		} catch (Throwable $error) {
			$errorMessage = 'We could not look up your reservation right now. Please try again.';
		}
	}
}

function escape(string $value): string {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatReservationDate(string $date): string {
	$parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
	return $parsedDate === false ? escape($date) : $parsedDate->format('F j, Y');
}

function getNumberOfNights(string $checkIn, string $checkOut): int|string {
	try {
		$checkInDate = new DateTimeImmutable($checkIn);
		$checkOutDate = new DateTimeImmutable($checkOut);
		return (int) $checkInDate->diff($checkOutDate)->days;
	} catch (Exception $error) {
		return 'Unavailable';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Look Up Reservation</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="base.css">
	<link rel="stylesheet" href="lookup_reservation.css">
</head>
<body>
	<?php require 'header.php'; ?>

	<main class="lookup-main">
		<section class="lookup-heading" aria-labelledby="lookup-title">
			<h1 id="lookup-title">Look Up My Reservation</h1>
			<p>Find your booking using the email address on the reservation or your MBR confirmation number.</p>
		</section>

		<?php if ($errorMessage !== '') { ?>
			<div class="lookup-message lookup-error" role="alert">
				<?= escape($errorMessage) ?>
			</div>
		<?php } ?>

		<section class="lookup-methods" aria-label="Reservation lookup methods">
			<form class="lookup-card" method="post" action="lookup_reservation.php">
				<input type="hidden" name="search_method" value="email">
				<h2>Look Up by Email</h2>
				<p>Use the email address associated with your account.</p>

				<label for="email">Email Address</label>
				<input
					type="email"
					name="email"
					id="email"
					value="<?= escape($email) ?>"
					autocomplete="email"
					maxlength="100"
					required>

				<button type="submit">Find My Reservations</button>
			</form>

			<form class="lookup-card" method="post" action="lookup_reservation.php">
				<input type="hidden" name="search_method" value="confirmation">
				<h2>Look Up by MBR Number</h2>
				<p>Use the confirmation number provided when you booked.</p>

				<label for="confirmation_number">MBR Confirmation Number</label>
				<input
					type="text"
					name="confirmation_number"
					id="confirmation_number"
					value="<?= escape($confirmationNumber) ?>"
					placeholder="MBR-123456"
					pattern="[Mm][Bb][Rr]-[0-9]{6}"
					title="Enter MBR followed by a hyphen and six digits, such as MBR-123456."
					maxlength="10"
					required>

				<button type="submit">Find My Reservation</button>
			</form>
		</section>

		<?php if (count($reservations) > 0) { ?>
			<section class="lookup-results" aria-labelledby="results-title">
				<h2 id="results-title">
					<?= count($reservations) === 1 ? 'Reservation Found' : 'Reservations Found' ?>
				</h2>

				<?php foreach ($reservations as $reservation) {
					$roomType = $roomTypes[(int) $reservation->RoomTypeId] ?? false;
					$roomName = $roomType === false ? 'Unavailable' : (string) $roomType->Name;
					$nightlyRate = $roomType === false
						? 'Unavailable'
						: '$' . number_format((float) $roomType->NightlyRate, 2);
					$specialRequests = trim((string) ($reservation->SpecialRequests ?? ''));
				?>
					<article class="reservation-card">
						<h3><?= escape((string) $reservation->ConfirmationNumber) ?></h3>

						<dl class="reservation-details">
							<div><dt>Room Type</dt><dd><?= escape($roomName) ?></dd></div>
							<div><dt>Rate per Night</dt><dd><?= escape($nightlyRate) ?></dd></div>
							<div><dt>Check-in Date</dt><dd><?= formatReservationDate((string) $reservation->CheckIn) ?></dd></div>
							<div><dt>Check-out Date</dt><dd><?= formatReservationDate((string) $reservation->CheckOut) ?></dd></div>
							<div><dt>Number of Nights</dt><dd><?= escape((string) getNumberOfNights((string) $reservation->CheckIn, (string) $reservation->CheckOut)) ?></dd></div>
							<div><dt>Number of Guests</dt><dd><?= escape((string) $reservation->GuestCount) ?></dd></div>
							<div><dt>Total Cost</dt><dd>$<?= number_format((float) $reservation->QuotedPrice, 2) ?></dd></div>
							<div class="full-row"><dt>Special Requests</dt><dd><?= $specialRequests === '' ? 'None' : nl2br(escape($specialRequests)) ?></dd></div>
						</dl>
					</article>
				<?php } ?>
			</section>
		<?php } ?>
	</main>
</body>
</html>
