<?php

// Start session
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

// Include database connection file
include 'includes/db_connection.php';

// Prepare a select statement
$sql = "SELECT reviews.review_id, rooms.room_name, reviews.review_text, reviews.review_date 
        FROM reviews 
        JOIN rooms ON reviews.room_id = rooms.room_id 
        WHERE reviews.user_id = ?";


if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param('i', $param_user_id);

    // Set parameters
    $param_user_id = $_SESSION['id'];

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch reviews and store them in an array
        $reviews = [];
        while ($review = $result->fetch_assoc()) {
            $reviews[] = $review;
        }
    } else {
        echo 'Ups! Coś poszło nie tak. Spróbuj ponownie później.';
    }

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Moje Opinie</title>
    <link rel='stylesheet' href='./assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Moje Opinie</h1>
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
                <h2>Twoje Opinie</h2>
                <div class="reviews-container">
                    <?php foreach ($reviews as $review): ?>
                        <div class='review review-card'>
                            <p>
                                <?php echo $review['review_text']; ?>
                            </p>
                            <small>Pokój:
                                <?php echo $review['room_name']; ?>
                            </small>
                            <br>
                            <small>Data opinii:
                                <?php echo $review['review_date']; ?>
                            </small>

                            <form action='edit_review.php' method='post'>
                                <input type='hidden' name='review_id' value='<?= $review['review_id'] ?>'>
                                <textarea name='review_text'><?= $review['review_text'] ?></textarea>
                                <input type='submit' value='Edytuj'>
                            </form>

                            <form action='delete_review.php' method='post'>
                                <input type='hidden' name='review_id' value='<?= $review['review_id'] ?>'>
                                <input type='submit' value='Usuń'>
                            </form>
                        </div>
                    <?php endforeach; ?>

            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>