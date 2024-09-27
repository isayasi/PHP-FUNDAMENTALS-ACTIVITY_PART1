<?php
session_start();

// Dummy user data for login
$users = [
    'Quenzzy' => password_hash('12345', PASSWORD_BCRYPT)
];

// Initialize an error variable
$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user is already logged in
    if (isset($_SESSION['logged_in_user']) && $_SESSION['logged_in_user'] == $username) {
        $error = "$username is already logged in. Wait for her to logout first.";
    } elseif (array_key_exists($username, $users) && password_verify($password, $users[$username])) {
        // Valid login, store the user session
        $_SESSION['logged_in_user'] = $username;
        $_SESSION['hashed_password'] = $users[$username];
    } else {
        // Invalid login
        $error = "Invalid username or password.";
    }
}

if (isset($_POST['logout'])) {
    // Clear session and logout
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            margin-bottom: 15px;
        }
        button {
            margin-right: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Always display the login form -->
<form method="POST" action="">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
    <br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit" name="login">Login</button>
    <button type="submit" name="logout">Logout</button>
</form>

<?php if (isset($_SESSION['logged_in_user'])): ?>
    <!-- Display logged in info after successful login -->
    <p><strong>User logged in:</strong> <?= htmlspecialchars($_SESSION['logged_in_user']); ?></p>
    <p><strong>Password:</strong> <?= $_SESSION['hashed_password']; ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <!-- Display error messages, if any -->
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

</body>
</html>
