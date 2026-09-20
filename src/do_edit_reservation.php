<?php declare(strict_types = 1);

require_once("database_capability.php");
$db = new ReadWriteCapability();

if (!$db->sessionHasPermission(Permission::EDIT_OTHER_RESERVATION)) {
	header('Location: unauthorized.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: generic_error.php');
	exit;
}

// make sure all the fields are populated - but as we have done some stricter permissions checks,
// we can do less validation

$valid =
	!empty($_POST['id']) &&
	isset($_POST['roomType']) && is_numeric($_POST['roomType']) && // roomType of zero is valid
	!empty($_POST['checkIn']) &&
	!empty($_POST['checkOut']) &&
	!empty($_POST['guestCount']) &&
	!empty($_POST['quotedPrice']) &&
	//true;
	isset($_POST['specialRequests']);

if (!$valid) {
	header('Location: generic_error.php');
	exit;
}

try {
	$res = $db->getReservation((int) $_POST['id']);
	if ($res === false) {
		header('Location: generic_error.php');
		exit;
	}

	$checkIn = new DateTimeImmutable($_POST['checkIn']);
	$checkOut = new DateTimeImmutable($_POST['checkOut']);

	$res->RoomTypeId = (int) $_POST['roomType'];
	$res->CheckIn = $checkIn->format('Y-m-d');
	$res->CheckOut = $checkOut->format('Y-m-d');
	$res->GuestCount = (int) $_POST['guestCount'];
	// We don't normalize this to float to prevent precision loss
	$res->QuotedPrice = $_POST['quotedPrice'];
	$res->SpecialRequests = $_POST['specialRequests'];

	if ($db->updateReservation($res)) {
		header('Location: view_reservation.php?r=' . $res->ConfirmationNumber);
		exit;
	} else {
		header('Location: generic_error.php');
		exit;
	}
} catch (Exception $e) {
	header('Location: generic_error.php');
	exit;
} finally {
	unset($db);
}

?>
