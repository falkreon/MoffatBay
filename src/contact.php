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