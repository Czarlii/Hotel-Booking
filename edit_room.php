<?php
session_start();
// Include database connection file
include 'includes/db_connection.php';
include 'admin_check.php';

$room = [];

// Check if room_id is set in URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Get room details from database based on room_id
    $sql = 'SELECT * FROM rooms WHERE room_id = ?';
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $room_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $room = $result->fetch_assoc();
            } else {
                echo 'Nie znaleziono pokoju o podanym ID.';
                exit;
            }
        } else {
            echo 'Coś poszło nie tak. Proszę spróbować później.';
        }
        $stmt->close();
    }

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get updated room details from form
        $room_name = $_POST['room_name'];
        $room_description = $_POST['room_description'];
        $price = $_POST['price'];

        // Update room details in database
        $sql = 'UPDATE rooms SET room_name = ?, room_description = ?, price = ? WHERE room_id = ?';
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssdi', $room_name, $room_description, $price, $room_id);
            if ($stmt->execute()) {
                header('location: rooms_list.php');
            } else {
                echo 'Coś poszło nie tak podczas aktualizacji. Proszę spróbować później.';
            }
            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Edytuj Pokój</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Edytuj Pokój</h1>
    </header>
    <div class="centered-container">
        <main>
            <form action='edit_room.php?room_id=<?php echo $room_id; ?>' method='post'>
                <div class='form-group'>
                    <label>Nazwa Pokoju</label>
                    <input type='text' name='room_name'
                        value='<?php echo isset($room['room_name']) ? $room['room_name'] : ''; ?>'>
                </div>
                <div class='form-group'>
                    <label>Opis</label>
                    <textarea
                        name='room_description'><?php echo isset($room['room_description']) ? $room['room_description'] : ''; ?></textarea>
                </div>
                <div class='form-group'>
                    <label>Cena (PLN)</label>
                    <input type='number' name='price'
                        value='<?php echo isset($room['price']) ? $room['price'] : ''; ?>'>
                </div>
                <div class='form-group'>
                    <input type='submit' value='Aktualizuj Pokój'>
                </div>
            </form>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>