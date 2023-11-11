<?php

// Adres e-mail, na który mają być wysyłane wiadomości
$recipient_email = "karol.przewuski@outlook.com";
$subject = "Nowa wiadomosc z formularza kontaktowego";

// Pobieranie danych z formularza
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Tworzenie treści wiadomości
$email_content = "Imię i Nazwisko: $name\n";
$email_content .= "Email: $email\n\n";
$email_content .= "Wiadomość:\n$message\n";

// Ustawienie nagłówków e-mail
$headers = "From: $name  <karol.przewuski@outlook.com>\r\n";
$headers .= "Reply-To: $name <$email>";

// Wysyłanie wiadomości e-mail
if (mail($recipient_email, $subject, $email_content, $headers)) {
    echo "Dziękujemy za wysłanie wiadomości!";
} else {
    echo "Nie udało się wysłać wiadomości. Proszę spróbować później.";
}

?>

<a href="contact.php">Powrót do strony kontaktowej</a>