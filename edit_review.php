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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review_id']) && isset($_POST['review_text'])) {
    $reviewId = $_POST['review_id'];
    $reviewText = $_POST['review_text'];

    // Update review in the database
    $sql = "UPDATE reviews SET review_text = ? WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $reviewText, $reviewId);
    if ($stmt->execute()) {
        header('location: my_reviews.php?review_updated=success');
        exit;
    } else {
        echo 'Ups! Coś poszło nie tak. Spróbuj ponownie później.';
    }
    $stmt->close();
}

$conn->close();
?>