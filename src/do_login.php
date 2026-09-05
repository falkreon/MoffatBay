<?php declare(strict_types=1);
session_start();

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/6/2026
 */

require_once('database_capability.php');

/**
 * Validate that the expected login fields were submitted.
 * Credential failures intentionally use one generic error message so that
 * the login page never reveals whether an account exists.
 */
function validateLoginData(): array|false {
	if (!array_key_exists('email', $_POST)) return false;
	if (!array_key_exists('password', $_POST)) return false;

	if ($_POST['email'] === null) return false;
	if ($_POST['password'] === null) return false;

	$email = trim($_POST['email']);
	$password = $_POST['password'];

	if ($email === '') return false;
	if ($password === '') return false;

	return [
		'email' => $email,
		'password' => $password
	];
}

$form = validateLoginData();

if ($form === false) {
	header('Location: login.php?error=1');
	exit;
}

try {
	$db = new ReadCapability();
	$user = $db->authenticateUser($form['email'], $form['password']);
	unset($db);

	if ($user === false) {
		header('Location: login.php?error=1');
		exit;
	}

	// Authentication succeeded. Regenerate the session ID before storing
	// the authenticated user's ID to reduce session-fixation risk.
	session_regenerate_id(true);
	$_SESSION['user_id'] = $user->Id;

	header('Location: user_home.php');
	exit;

} catch (Throwable $e) {
	// Do not display database or server details to the user.
	header('Location: login_error.php');
	exit;
}
?>
