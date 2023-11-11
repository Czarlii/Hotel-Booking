<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_booking";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

?>