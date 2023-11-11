<?php

// Sprawdza czy uzytkownik jest zalogowany
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

// Sprawdza czy USer jest adminem
if ($_SESSION['role'] !== 'admin') {
    header('location: index.php');
    exit;
}

?>