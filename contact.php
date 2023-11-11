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
    <title>Kontakt</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Kontakt z Nami</h1>
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
    <main>
        <section class='contact-info'>
            <h2>Informacje Kontaktowe</h2>
            <p>Adres: ul. Przykładowa 123, 00-000 Miasto</p>
            <p>Telefon: +48 123 456 789</p>
            <p>Email: kontakt@naszhotel.pl</p>

            <h2>Formularz Kontaktowy</h2>
            <form action='send_message.php' method='post'>
                <div class='form-group'>
                    <label>Imię i Nazwisko</label>
                    <input type='text' name='name' required>
                </div>
                <div class='form-group'>
                    <label>Email</label>
                    <input type='email' name='email' required>
                </div>
                <div class='form-group'>
                    <label>Wiadomość</label>
                    <textarea name='message' rows='5' required></textarea>
                </div>
                <div class='form-group'>
                    <input type='submit' value='Wyślij Wiadomość'>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>