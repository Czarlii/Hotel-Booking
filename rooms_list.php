<?php

// Start session
session_start();

// Include database connection file
include 'includes/db_connection.php';
include 'admin_check.php';

// Fetch all rooms from the database
$sql = 'SELECT * FROM rooms';
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Lista Pokojów</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Lista Dostępnych Pokoi</h1>
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
            <div class="rooms">
                <?php
                while ($room = $result->fetch_assoc()) {
                    echo '<div class="room-card">';
                    echo '<img src="' . $room['room_image'] . '" alt="Zdjęcie pokoju">';
                    echo '<h3>' . $room['room_name'] . '</h3>';
                    echo '<p>' . $room['room_description'] . '</p>';
                    echo '<p>Cena: ' . $room['price'] . ' PLN</p>';
                    echo '<div class="action-buttons">';
                    echo '<a href="edit_room.php?room_id=' . $room['room_id'] . '">Edytuj</a>';
                    echo '<a href="delete_room.php?room_id=' . $room['room_id'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć ten pokój?\');">Usuń</a>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>