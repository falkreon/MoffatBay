<?php declare(strict_types = 1);
session_start();

/**
 * CSD460: Capstone in Software Development
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 9/22/2026
 */

require_once('database_capability.php');

$db = new ReadWriteCapability();
try {
	// TODO: Add an "Edit Contact" permission?
	if (!$db->sessionHasPermission(Permission::VIEW_OTHER_CONTACT)) {
		header('Location: unauthorized.php');
		exit;
	}
	
	if (!(isset($_POST['id']) && isset($_POST['resolved']))) {
		header('Location: generic_error.php');
		exit;
	}
	
	$message = $db->getContactMessage((int) $_POST['id']);
	switch($_POST['resolved']) {
		case 'resolved':
			$db->setMessageStatus((int) $_POST['id'], 'Resolved');
			break;
		case 'unresolved':
			$db->setMessageStatus((int) $_POST['id'], 'Unresolved');
			break;
		default:
			header('Location: generic_error.php');
			exit;
	}
	
	
} finally {
	unset($db);
	header('Location: view_contact.php?id=' . (int) $_POST['id']);
}

?>


