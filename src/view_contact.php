<?php declare(strict_types=1);
session_start();

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/21/2026
 */

require_once('database_capability.php');

try {
	$db = new ReadCapability();

	// Are we authorized to view this page?
	if (!$db->sessionHasPermission(Permission::VIEW_OTHER_CONTACT)) {
		header('Location: unauthorized.php');
		exit;
	}
	$showUserLinks = $db->sessionHasPermission(Permission::VIEW_OTHER_USER);

	if (!isset($_GET['id'])) {
		header('Location: generic_error.php');
		exit;
	}

	$message = $db->getContactMessage((int) $_GET['id']);

	if ($message === false) {
		header('Location: generic_error.php');
		exit;
	}


	$user = false;
	if ($message->UserId != NULL && $message->UserId != '') {
		$user = $db->getUser((int) $message->UserId); // This could be false but that's allowed!
	}
} catch (Throwable $e) {
	header('Location: generic_error.php');
	exit;
} finally {
	unset($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Contact messages</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<?php require 'header.php'; ?>
	<div class="center">
	<section>
		<h1><?= $message->Subject ?></h1>
		<?php if ($user !== false && $showUserLinks) { ?>
			<p>User: <a href="user_home.php?user=<?= $user->Id ?>"><?= $user->FirstName ?> <?= $user->LastName ?> (<?= $user->Email ?>)</a>
		<?php } ?>
		<p>Full Name: <?= htmlspecialchars($message->FullName) ?>
		<p>Email: <a href="mailto:<?= htmlspecialchars($message->Email) ?>"><?= htmlspecialchars($message->Email) ?></a>
		<?php if ($message->Phone !== null) { ?>
		<p>Telephone: <a href="tel:<?= htmlspecialchars($message->Phone) ?>"><?= htmlspecialchars($message->Phone) ?></a>
		<?php } ?>
		<p>CreatedAt: <?php $createdAt = new DateTimeImmutable($message->CreatedAt); echo $createdAt->format('Y-m-d'); ?>
		<p>Status: <?= $message->Status ?>
		<p>Message:
		<p><?= nl2br(htmlspecialchars($message->Message)) ?>
		<p><button class="button" onclick="history.go(-1);">Back </button>
	</section>
	</div>
</body>
</html>
