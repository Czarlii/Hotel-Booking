<?php
session_start();
// Dołączenie plików 
include 'includes/db_connection.php';
include 'admin_check.php';

// Definicja zmiennych
$room_name = $description = $price = '';
$image_path = 'assets/images/default.jpg'; 
$room_name_err = $description_err = $price_err = '';

//Sprawadza czy formularz przeslany
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sprawdza czy istnieje nazwa pokoju
    if (empty(trim($_POST['room_name']))) {
        $room_name_err = 'Proszę podać nazwę pokoju.';
    } else {
        $room_name = trim($_POST['room_name']);
    }

    // Sprawdza czy istnieje opis
    if (empty(trim($_POST['description']))) {
        $description_err = 'Proszę podać opis pokoju.';
    } else {
        $description = trim($_POST['description']);
    }

    // Sprawdza czy cena nie jest pusta i czy jest numerem
    if (empty(trim($_POST['price'])) || !is_numeric($_POST['price'])) {
        $price_err = 'Proszę podać prawidłową cenę pokoju.';
    } else {
        $price = trim($_POST['price']);
    }

    // Sprawdzcza czy zdjęcie jest 
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] == 0) {
        $target_dir = 'assets/images/';
        $target_file = $target_dir . basename($_FILES['room_image']['name']);
        $image_path = $target_file;
        move_uploaded_file($_FILES['room_image']['tmp_name'], $target_file);
    }

    // Sprawdza wejsciowe bledy zanim zacznie operacje na bazach
    if (empty($room_name_err) && empty($description_err) && empty($price_err)) {
        $sql = 'INSERT INTO rooms (room_name, room_description, price, room_image, is_available) VALUES (?, ?, ?, ?, 1)';


        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssss', $room_name, $description, $price, $image_path);
            if ($stmt->execute()) {
                header('location: rooms_list.php');
            } else {
                echo 'Coś poszło nie tak. Proszę spróbować później.';
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
    <title>Dodaj Pokój</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <header>
        <h1>Dodaj Nowy Pokój</h1>
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
            <form action='add_room.php' method='post' enctype='multipart/form-data'>
                <div class='form-group'>
                    <label>Nazwa Pokoju</label>
                    <input type='text' name='room_name' value='<?php echo $room_name; ?>'>
                    <span class='error'>
                        <?php echo $room_name_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Opis</label>
                    <textarea name='description'><?php echo $description; ?></textarea>
                    <span class='error'>
                        <?php echo $description_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Cena (PLN)</label>
                    <input type='number' name='price' value='<?php echo $price; ?>'>
                    <span class='error'>
                        <?php echo $price_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Zdjęcie Pokoju</label>
                    <input type='file' name='room_image'>
                </div>
                <div class='form-group'>
                    <input type='submit' value='Dodaj Pokój'>
                </div>
            </form>
        </main>
    </div>
    <footer>
        <p>&copy; 2023 System Rezerwacji Hotelowej. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>