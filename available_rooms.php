<?php
session_start();

// Include database connection file
include 'includes/db_connection.php';

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

function getRoomEndDate($roomId)
{
    global $conn;
    $sql = "SELECT end_date FROM reservations WHERE room_id = ? AND end_date >= CURDATE() ORDER BY end_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['end_date'];
    }
    return false;
}

//Logika szukania
$minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : PHP_INT_MAX;
$availability = isset($_GET['availability']) ? $_GET['availability'] : 'all';
$sortPrice = isset($_GET['sort_price']) && $_GET['sort_price'] == 'desc' ? 'DESC' : 'ASC';

$whereClause = "WHERE price BETWEEN $minPrice AND $maxPrice";
if ($availability == 'available') {
    $whereClause .= " AND is_available = 1";
} elseif ($availability == 'booked') {
    $whereClause .= " AND is_available = 0";
}

$sql = "SELECT * FROM rooms $whereClause  ORDER BY price $sortPrice";
$result = $conn->query($sql);
$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dostępne Pokoje</title>
    <link rel='stylesheet' href='./assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Dostępne Pokoje</h1>
    </header>

    <?php if (isset($_GET['booked']) && $_GET['booked'] == 'success'): ?>
        <script>
            alert('Rezerwacja została pomyślnie dodana!');
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['room_released']) && $_GET['room_released'] == 'success'): ?>
        <script>
            alert('Pokój został pomyślnie oddany!');
        </script>
    <?php endif; ?>
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
            <section class='rooms'>
                <div class="search-form">
                    <form action="available_rooms.php" method="get">
                        Cena od: <input type="number" name="min_price" min="0" step="0.01">
                        Cena do: <input type="number" name="max_price" min="0" step="0.01">
                        Dostępność:
                        <select name="availability">
                            <option value="all">Wszystkie</option>
                            <option value="available">Dostępne</option>
                            <option value="booked">Zarezerwowane</option>
                        </select>
                        Sortuj według ceny:
                        <select name="sort_price">
                            <option value="asc">Rosnąco</option>
                            <option value="desc">Malejąco</option>
                        </select>
                        <input type="submit" value="Wyszukaj" class="btn">
                    </form>
                </div>
                <?php foreach ($rooms as $room) {
                    $endDate = getRoomEndDate($room['room_id'])
                        ?>
                    <div class='room-card <?php echo $endDate ? "booked" : ""; ?>'>
                        <img src='<?php echo $room['room_image']; ?>' alt='Zdjęcie pokoju'>
                        <h3>
                            <?php echo $room['room_name']; ?>
                        </h3>
                        <p>
                            <?php echo $room['room_description']; ?>
                        </p>
                        <p>Cena:
                            <?php echo $room['price']; ?> PLN
                        </p>
                        <?php if (!$endDate) { ?>
                            <a href='book_room.php?id=<?php echo $room['room_id']; ?>' class='btn'>Zarezerwuj</a>
                        <?php } else { ?>
                            <span class="booked-info">Zarezerwowany do
                                <?php echo $endDate; ?>
                            </span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>