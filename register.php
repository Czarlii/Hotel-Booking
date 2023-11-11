<?php

// Include database connection file
include 'includes/db_connection.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = $first_name = $last_name = $email = '';
$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $email_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Proszę wprowadzić nazwę użytkownika.';
    } else {
        // Prepare a select statement
        $sql = 'SELECT user_id FROM users WHERE username = ?';

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_username);

            // Set parameters
            $param_username = trim($_POST['username']);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = 'Ta nazwa użytkownika jest już zajęta.';
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo 'Ups! Coś poszło nie tak. Spróbuj ponownie później.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Proszę wprowadzić hasło.';
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = 'Hasło musi mieć co najmniej 6 znaków.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Proszę potwierdzić hasło.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = 'Hasła nie pasują do siebie.';
        }
    }

    // Validate first name, last name, and email
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    if (empty($first_name)) {
        $first_name_err = 'Proszę wprowadzić imię.';
    }
    if (empty($last_name)) {
        $last_name_err = 'Proszę wprowadzić nazwisko.';
    }
    if (empty($email)) {
        $email_err = 'Proszę wprowadzić adres e-mail.';
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($email_err)) {

        // Prepare an insert statement
        $sql = 'INSERT INTO users (username, password, first_name, last_name, email) VALUES (?, ?, ?, ?, ?)';

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('sssss', $param_username, $param_password, $param_first_name, $param_last_name, $param_email);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header('location: login.php');
            } else {
                echo 'Coś poszło nie tak. Spróbuj ponownie później.';
            }

            // Close statement
            $stmt->close();
        }
    }


}

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Rejestracja</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <div class="centered-container">
        <div class='register-form'>
            <h2>Rejestracja</h2>
            <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method='post'>
                <div class='form-group'>
                    <label>Login</label>
                    <input type='text' name='username' class='form-control' value='<?php echo $username; ?>'>
                    <span class='help-block'>
                        <?php echo $username_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Hasło</label>
                    <input type='password' name='password' class='form-control' value='<?php echo $password; ?>'>
                    <span class='help-block'>
                        <?php echo $password_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Potwierdź hasło</label>
                    <input type='password' name='confirm_password' class='form-control'
                        value='<?php echo $confirm_password; ?>'>
                    <span class='help-block'>
                        <?php echo $confirm_password_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Imię</label>
                    <input type='text' name='first_name' class='form-control' value='<?php echo $first_name; ?>'>
                    <span class='help-block'>
                        <?php echo $first_name_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Nazwisko</label>
                    <input type='text' name='last_name' class='form-control' value='<?php echo $last_name; ?>'>
                    <span class='help-block'>
                        <?php echo $last_name_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <label>Email</label>
                    <input type='email' name='email' class='form-control' value='<?php echo $email; ?>'>
                    <span class='help-block'>
                        <?php echo $email_err; ?>
                    </span>
                </div>
                <div class='form-group'>
                    <input type='submit' class='btn btn-primary' value='Zarejestruj'>
                </div>
                <p>Masz już konto? <a href='login.php'>Zaloguj się</a>.</p>
            </form>
        </div>
    </div>
</body>

</html>