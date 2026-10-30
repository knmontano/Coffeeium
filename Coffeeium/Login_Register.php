<?php
session_start();
require_once 'config.php'; 

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
   
    $check_email = $con->query("SELECT * FROM logs_reg WHERE email='$email'");

    if ($check_email->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register';  
        header('location: login.php'); 
        exit();
    } else {
        $con->query("INSERT INTO logs_reg (name, email, password) VALUES ('$name', '$email', '$password')");
        header('location: login.php'); 
        exit();
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $con->query("SELECT * FROM logs_reg WHERE email='$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            header('location: main.php'); 
            exit();
        } else {
            $_SESSION['login_error'] = 'Incorrect password!';
            $_SESSION['active_form'] = 'login';
            header('location: main.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'User not found.';
        $_SESSION['active_form'] = 'login';
        header('location: login.php');
        exit();
    }
}
?>