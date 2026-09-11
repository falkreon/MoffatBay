<?php
declare(strict_types=1);

session_start();

$error = $_GET['err'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reservation Error</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="base.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            font-family: "Montserrat", sans-serif;
            font-weight: 600;   
        }
        
        body {
            min-height: 100vh;
            background: var(--accent-gradient);
            margin:0;
        }

        .section-title {
            text-align: center;
        }

       .error-links {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        padding: 2rem;
    }
    </style>
</head>
<body>
    <?php require 'header.php'; ?>
    <section class="section-title">
        <h1>Error</h1>
        <?php if ($error === 'login-needed'): ?>
            <p>You need to register or log in to make a reservation.</p>
        <?php else: ?>
            <p>An unknown error occurred.</p>
        <?php endif; ?>
    </section>
    <div class="error-links">
        <a href="login.php">Go to login page</a>
        <a href="register.php">Go to registration page</a>
    </div>
</body>
</html>