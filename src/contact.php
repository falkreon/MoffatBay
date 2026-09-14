<?php
declare(strict_types=1);

session_start();

/**
 * CSD460: Capstone in Software Development
 * Moffat Bay Lodge
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 */

require_once('database_capability.php');


function validateContactFormData(): ContactMessage|false
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }

    if (!array_key_exists('name', $_POST)) return false;
    if (!array_key_exists('email', $_POST)) return false;
    if (!array_key_exists('phone', $_POST)) return false;
    if (!array_key_exists('subject', $_POST)) return false;
    if (!array_key_exists('message', $_POST)) return false;

    $fullName = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($fullName === '') return false;
    if ($email === '') return false;
    if ($subject === '') return false;
    if ($message === '') return false;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $contactMessage = new ContactMessage();

    $contactMessage->UserId = $_SESSION['user_id'] ?? null;
    $contactMessage->FullName = $fullName;
    $contactMessage->Email = $email;
    $contactMessage->Phone = ($phone === '') ? null : $phone;
    $contactMessage->Subject = $subject;
    $contactMessage->Message = $message;

    return $contactMessage;
}


$contactSuccess = false;
$contactError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = validateContactFormData();

    if ($form === false) {

        $contactError = true;

    } else {

        $db = new ReadWriteCapability();

        $id = $db->createContactMessage($form);

        if ($id === false) {
            $contactError = true;
        } else {
            $contactSuccess = true;
        }

        unset($db);
    }
}
?>
<?php if ($contactSuccess): ?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Moffat Bay Lodge</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="base.css?v=20260914">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

	<body class="contact-confirmation-page">
		<a name="top"></a>
		<?php require 'header.php'; ?>

		<main class="contact-confirmation-main">
			<section class="contact-confirmation-heading">
				<h1>Contact Us Confirmation</h1>
			</section>

			<section class="contact-confirmation-card">
				<h3>Message Received</h3>
				<p>Thank you for contacting Moffat Bay Lodge.</p>
				<p>We will get back to you as soon as we can.</p>

				<a class="contact-confirmation-link" href="index.php">Return to Homepage</a>
			</section>
		</main>
	</body>

</html>
<?php endif; ?>
