<?php
session_start();
include 'includes/db_connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $roomId = $_GET['id'];
    $userId = $_SESSION['id'];

    if (strtotime($startDate) <= strtotime($endDate)) {
        $sql = "INSERT INTO reservations (user_id, room_id, start_date, end_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iiss", $userId, $roomId, $startDate, $endDate);
            if ($stmt->execute()) {
                // Update the room availability
                $sqlUpdate = "UPDATE rooms SET is_available = 0 WHERE room_id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                if ($stmtUpdate) {
                    $stmtUpdate->bind_param("i", $roomId);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                }
                header('location: available_rooms.php?booked=success');
                exit;
            } else {
                $message = "Błąd podczas rezerwacji. Proszę spróbować ponownie.";
            }
            $stmt->close();
        } else {
            $message = "Błąd podczas rezerwacji. Proszę spróbować ponownie.";
        }
    } else {
        $message = "Data początkowa musi być wcześniejsza niż data końcowa!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja pokoju</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <div class='login-form'>
        <h2>Rezerwacja pokoju</h2>

        <?php if ($message): ?>
            <p style="color: red;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form action="" method="post">
            <div class='form-group'>
                <label for="start_date">Data początkowa:</label>
                <input type="date" name="start_date" required>
            </div>

            <div class='form-group'>
                <label for="end_date">Data końcowa:</label>
                <input type="date" name="end_date" required>
            </div>

            <div class='form-group'>
                <label for="name">Imię i Nazwisko:</label>
                <input type="text" name="name" required>
            </div>

            <div class='form-group'>
                <input type="submit" value="Rezerwuj" class='btn'>
            </div>
        </form>
    </div>
</body>

</html>