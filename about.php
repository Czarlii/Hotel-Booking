<?php
session_start();
// Dołącz plik łączący z bazą danych
include 'includes/db_connection.php';

// Sprawdź, czy użytkownik jest zalogowany; jeśli nie, przekieruj go do strony logowania
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
    <title>O Nas</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>O Naszym Hotelu</h1>
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
            <section class='about-info'>
                <h2>Historia Hotelu</h2>
                <p>Nasz hotel został założony w 1985 roku i od tego czasu służy gościom z całego świata. Przez lata
                    zdobyliśmy wiele nagród za doskonałą obsługę i komfort pobytu.</p>

                <h2>Misja i Wartości</h2>
                <p>Naszą misją jest zapewnienie gościom niezapomnianego pobytu w komfortowych warunkach. Cenimy sobie
                    doskonałą obsługę, dbałość o szczegóły i autentyczne doświadczenia.</p>

                <h2>Zespół</h2>
                <p>Nasz zespół składa się z profesjonalistów, którzy są pasjonatami branży hotelarskiej. Każdy z nas
                    jest
                    gotów służyć pomocą i uczynić Twój pobyt niezapomnianym.</p>
            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>