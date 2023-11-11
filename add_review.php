<?php
// Rozpoczecie sesji
session_start();

// SPrawdzenie czy zalogowany
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

//Dołączenie pliku z połączeniem
include 'includes/db_connection.php';

$message = "";

// Sprawdzenie czy formularz przeslany
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_id']) && isset($_POST['review_text'])) {
    $roomId = $_POST['room_id'];
    $reviewText = $_POST['review_text'];
    $userId = $_SESSION['id'];

    // Wpisuje opinie do bazy danych
    $sql = "INSERT INTO reviews (user_id, room_id, review_text, review_date) VALUES (?, ?, ?, CURRENT_DATE)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $userId, $roomId, $reviewText); 
    if ($stmt->execute()) {
        header('location: reviews.php?review_added=success');
        exit;
    } else {
        $message = "Błąd podczas dodawania opinii: " . $stmt->error;
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
    <title>Dodaj Opinię</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Dodaj Opinię</h1>
    </header>
    <div class="centered-container">
        <main>

            <section class='add-review'>
                <h2>Dodaj swoją opinię</h2>
                <?php if (!empty($message)): ?>
                    <p>
                        <?= $message ?>
                    </p>
                <?php endif; ?>
                <form action='add_review.php' method='post'>
                    <input type='hidden' name='room_id'
                        value='<?= isset($_POST['room_id']) ? $_POST['room_id'] : '' ?>'>
                    <textarea name='review_text' placeholder='Wpisz swoją opinię'></textarea>
                    <input type='submit' value='Dodaj Opinię'>
                </form>
            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>