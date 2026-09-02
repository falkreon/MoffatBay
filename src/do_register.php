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
const ROLE_CUSTOMER = 2;

function validateFormData(): array|false {
	// All form fields are required. If any are missing or null we should bail
	if (!array_key_exists('email', $_POST)) return false;
	if (!array_key_exists('firstName', $_POST)) return false;
	if (!array_key_exists('lastName', $_POST)) return false;
	if (!array_key_exists('phone', $_POST)) return false;
	if (!array_key_exists('password', $_POST)) return false;

	if ($_POST['email'] === null) return false;
	if ($_POST['firstName'] === null) return false;
	if ($_POST['lastName'] === null) return false;
	if ($_POST['phone'] === null) return false;
	if ($_POST['password'] === null) return false;

	$result = [];
	$user = new User();

	// Apply same validation rule from the form field, in case the user has manually
	// inserted the fields into the URL
	if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $_POST['email'])) return false;
	$user->Email = $_POST['email'];

	if (preg_match('/^(?:\(([0-9]{3})\)[ ]?)|(?:([0-9]{3})[ ]?)?([0-9]{3})[ ]?-?[ ]?([0-9]{4})$/', $_POST['phone'], $matches)) {
		if ($matches[1] === '' && $matches[2] === '') {
			$areacode = '';
		} else {
			if ($matches[1] !== '') {
				$areacode = '(' . $matches[1] . ') ';
			} else {
				$areacode = '(' . $matches[2] . ') ';
			}
		}

		// TODO: Phone PCRE is broken! Only one area code form works!
		$user->PhoneNumber = $areacode . $matches[3] . '-' . $matches[4];
	} else {
		return false;
	}

	if ($_POST['firstName'] == '') return false;
	if ($_POST['lastName'] == '') return false;
	$user->FirstName = $_POST['firstName'];
	$user->LastName = $_POST['lastName'];

	$user->RoleId = ROLE_CUSTOMER;

	//The password is the trickiest part!
	if (!preg_match(
		'/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,}$/',
		$_POST['password']
	)) return false;
	$result['password'] = $_POST['password'];
	$result['user'] = $user;

	return $result;
}



$form = validateFormData();
$db = new ReadWriteCapability();
{
	$id = $db->createUser($form['user'], $form['password']);
	if ($id === false) {
		echo('Failed to create new user!');
		// TODO: Redirect customer to a useful error page.
	} else {
		echo('New User Created: ' . $id);
		// TODO: Redirect the user to their user-home
	}

}
unset($db);
?>
<html>
<body>
<pre>
<?php
$form = validateFormData();
if ($form) {
	print_r($form);
} else {
	echo("FALSE");
}
?>
</pre>
</body>
</html>
