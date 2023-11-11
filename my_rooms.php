<?php

// Start session
session_start();

// Check if user is already logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

// Include database connection file
include 'includes/db_connection.php';

$rooms = [];

// Fetch rooms reserved by the user
$sql = 'SELECT rooms.room_id, rooms.room_name, reservations.start_date, reservations.end_date, reservations.reservation_id 
        FROM reservations 
        JOIN rooms ON reservations.room_id = rooms.room_id 
        WHERE reservations.user_id = ?';



if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $_SESSION['id']); 
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $rooms = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Moje Pokoje</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Moje Pokoje</h1>
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
            <section class='my-rooms'>
                <h2>Twoje rezerwacje</h2>
                <ul>
                    <?php foreach ($rooms as $room): ?>
                        <li class="room-card">
                            Pokój:
                            <?= $room['room_name'] ?><br>
                            Data rozpoczęcia rezerwacji:
                            <?= $room['start_date'] ?><br>
                            Data zakończenia rezerwacji:
                            <?= $room['end_date'] ?><br>


                            <form action='release_room.php' method='post'>
                                <input type='hidden' name='reservation_id' value='<?= $room['reservation_id'] ?>'>
                                <input type='submit' name='release_room' value='Oddaj Pokój'>
                            </form>

                            <form action='add_review.php' method='post'>
                                <input type='hidden' name='room_id' value='<?= $room['room_id'] ?>'>
                                <input type='submit' value='Dodaj Opinię'>
                            </form>

                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>