<?php

session_start();
include 'includes/db_connection.php';
include 'admin_check.php';

// Check if room_id is set in URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Delete room from database based on room_id
    $sql = 'DELETE FROM rooms WHERE room_id = ?';
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $room_id);
        if ($stmt->execute()) {
            header('location: rooms_list.php');
        } else {
            echo 'Coś poszło nie tak podczas usuwania. Proszę spróbować później.';
        }
        $stmt->close();
    }
    $conn->close();
} else {
    echo 'Nieprawidłowe ID pokoju.';
}
?>