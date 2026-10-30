<?php
session_start();
if (isset($_SESSION['email'])) {
    header('location: main.php');
    exit();
}

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];

$active_form = $_SESSION['active_form'] ?? 'login';
session_unset();

function show_error($error) {
    if (!empty($error)) {
        return '<p class="error-message">' . $error . '</p>'; 
    }
    return '';
}

function is_active_form($form_name, $active_form) {
    return ($form_name === $active_form) ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box <?php echo is_active_form('login', $active_form); ?> " id="login-Form">
            <form action="Login_Register.php" method="post">
                <h2>Login</h2>
                <?php echo show_error($errors['login']); ?>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="password" required>
                <button type="submit" name="login">Login</button>
                <p>Dont have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>

        <div class="form-box <?php echo is_active_form('register', $active_form); ?>" id="register-form">
            <form action="Login_Register.php" method="post">
                <h2>Register</h2>
                <?php echo show_error($errors['register']); ?>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="password" required>
                <button type="submit" name="register">Register</button>
                <p>Already have an account? <a href="#" onclick="showForm('login-Form')">Login</a></p>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>