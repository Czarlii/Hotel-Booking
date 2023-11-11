<?php
session_start();
include 'includes/db_connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservationId = $_POST['reservation_id'];
    $userId = $_SESSION['id'];

    // Check if the user owns the reservation
    $sql = "SELECT room_id FROM reservations WHERE reservation_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $reservationId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            // User owns the reservation, proceed to release the room
            $row = $result->fetch_assoc();
            $roomId = $row['room_id'];
            $stmt->close();

            // Delete the reservation
            $sql = "DELETE FROM reservations WHERE reservation_id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $reservationId);
                if ($stmt->execute()) {
                    // Update the room availability
                    $sqlUpdate = "UPDATE rooms SET is_available = 1 WHERE room_id = ?";
                    $stmtUpdate = $conn->prepare($sqlUpdate);
                    if ($stmtUpdate) {
                        $stmtUpdate->bind_param("i", $roomId);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }
                    header('location: available_rooms.php?room_released=success'); // Redirect user to available rooms page with success parameter
                    exit;
                }
            }
        } else {
            // User doesn't own the reservation
            $stmt->close();
        }
    }

    // If reservation doesn't exist or user doesn't own it
    header('location: my_rooms.php'); // Redirect user to "My Rooms" page
    exit;
}

// If request is not POST or 'reservation_id' parameter is missing
header('location: my_rooms.php'); // Redirect user to "My Rooms" page
exit;
?>