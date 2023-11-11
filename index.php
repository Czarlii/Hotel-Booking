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
    <title>Strona Główna</title>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel='stylesheet' href='./assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>System Rezerwacji Hotelowej</h1>
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
        <section class='intro'>
            <h2>Witaj w naszym systemie rezerwacji hotelowej!</h2>
            <div class="slider">
                <div><img class="small-image" src="./assets/images/hotel1.jpg" alt="Zdjęcie hotelu 1">
                </div>
                <div><img class="small-image" src="./assets/images/hotel2.jpg" alt="Zdjęcie hotelu 2">
                </div>
                <div><img class="small-image" src="./assets/images/hotel3.jpg" alt="Zdjęcie hotelu 3">
                </div>
                <div><img class="small-image" src="./assets/images/hotel4.jpg" alt="Zdjęcie hotelu 4">
                </div>
                <div><img class="small-image" src="./assets/images/hotel5.jpg" alt="Zdjęcie hotelu 5">
                </div>
                <div><img class="small-image" src="./assets/images/hotel6.jpg" alt="Zdjęcie hotelu 6">
                </div>
            </div>

            <p>Chcemy, aby Twoje doświadczenie z rezerwacją hotelu było jak najprostsze i najprzyjemniejsze. Dlatego
                stworzyliśmy ten system, który pozwala Ci łatwo przeglądać dostępne pokoje, dokonywać rezerwacji i
                czytać opinie innych gości.</p>
            <p>Przejrzyj dostępne pokoje i zarezerwuj swój wymarzony pobyt już dziś!</p>
            <a href='available_rooms.php' class='btn'>Przeglądaj pokoje</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script type="text/javascript" src="./assets/js/slider.js"></script>


</body>

</html>