<?php

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

// Include database connection file
include 'includes/db_connection.php';

// Fetch all reviews from the database
$sql = "SELECT rooms.room_name, rooms.room_image, reviews.review_text, users.first_name, users.last_name, reviews.review_date 
        FROM reviews 
        JOIN rooms ON reviews.room_id = rooms.room_id 
        JOIN users ON reviews.user_id = users.user_id";

$reviews = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Opinie</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Opinie</h1>
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
            <section class='user-reviews'>
                <div class='reviews-section'>
                    <h2 class="section-title">Opinie</h2>
                    <div class="reviews-grid"> 
                        <?php
                        if ($reviews->num_rows > 0) {
                            while ($review = $reviews->fetch_assoc()) {
                                echo "<div class='review-card room-card'>"; 
                                echo "<h3>Pokój: " . $review['room_name'] . "</h3>";
                                echo "<p>" . $review['review_text'] . "</p>";
                                echo "<span>Opinia dodana przez: " . $review['first_name'] . " " . $review['last_name'] . "</span>";
                                if (!empty($review['review_date'])) {
                                    echo "<br><span>Data opinii: " . $review['review_date'] . "</span>";
                                }
                                echo "</div>";
                            }
                        } else {
                            echo "<p>Brak opinii.</p>";
                        }

                        $conn->close();
                        ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>