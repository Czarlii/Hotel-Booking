<?php

// Start session
session_start();

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('location: index.php');
    exit;
}

// Include database connection file
include 'includes/db_connection.php';

// Define variables and initialize with empty values
$username = $password = '';
$username_err = $password_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if username is empty
    if (empty(trim($_POST['username']))) {
        $username_err = 'Proszę wprowadzić nazwę użytkownika.';
    } else {
        $username = trim($_POST['username']);
    }

    // Check if password is empty
    if (empty(trim($_POST['password']))) {
        $password_err = 'Proszę wprowadzić hasło.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = 'SELECT user_id, username, password, role FROM users WHERE username = ?';

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password, $role);

                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $role; // Store user role in session

                            // Redirect user to welcome page
                            header('location: index.php');
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'Hasło, które wprowadziłeś, jest niepoprawne.';
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = 'Nie znaleziono konta o tej nazwie użytkownika.';
                }
            } else {
                echo 'Ups! Coś poszło nie tak. Spróbuj ponownie później.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Logowanie</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>

<body>
    <div class='login-form'>
        <h2>Logowanie</h2>
        <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method='post'>
            <div class='form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>'>
                <label>Login</label>
                <input type='text' name='username' class='form-control' value='<?php echo $username; ?>'>
                <span class='help-block'>
                    <?php echo $username_err; ?>
                </span>
            </div>
            <div class='form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>'>
                <label>Hasło</label>
                <input type='password' name='password' class='form-control'>
                <span class='help-block'>
                    <?php echo $password_err; ?>
                </span>
            </div>
            <div class='form-group'>
                <input type='submit' class='btn btn-primary' value='Zaloguj'>
            </div>
            <p>Nie masz konta? <a href='register.php'>Zarejestruj się</a>.</p>
        </form>
    </div>
</body>

</html>