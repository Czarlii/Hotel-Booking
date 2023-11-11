<?php
session_start();
// Include database connection file
include 'includes/db_connection.php';

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Płatności</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Informacje o Płatnościach</h1>
    </header>
    <nav>
        <ul>
            <li><a href='index.php'>Strona Główna</a></li>
            <li><a href='available_rooms.php'>Pokoje</a></li>
            <li><a href='payments.php'>Płatności</a></li>
            <li><a href='about.php'>O Nas</a></li>
            <li><a href='contact.php'>Kontakt</a></li>
            <li><a href='reviews.php'>Opinie</a></li>
            <li><a href='my_rooms.php'>Moje Pokoje</a></li>
            <li><a href='my_reviews.php'>Moje Opinie</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li><a href='admin_panel.php'>Admin</a></li>
            <?php endif; ?>
            <li><a href='logout.php'>Wyloguj</a></li>
        </ul>
    </nav>
    <div class="centered-container">
        <main>
            <section class='payments-info'>
                <h2>Metody Płatności</h2>
                <p>W naszym hotelu akceptujemy następujące metody płatności:</p>
                <ul>
                    <li>Karty kredytowe/debetowe (Visa, MasterCard)</li>
                    <li>Przelew bankowy</li>
                    <li>Gotówka przy zameldowaniu</li>
                </ul>
                <p>Jeśli masz jakieś pytania dotyczące płatności, skontaktuj się z nami.</p>
            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>