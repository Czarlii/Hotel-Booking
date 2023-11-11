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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review_id'])) {
    $reviewId = $_POST['review_id'];

    // Delete review from the database
    $sql = "DELETE FROM reviews WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reviewId);
    if ($stmt->execute()) {
        header('location: my_reviews.php?review_deleted=success');
        exit;
    } else {
        echo 'Ups! Coś poszło nie tak. Spróbuj ponownie później.';
    }
    $stmt->close();
}

$conn->close();
?>