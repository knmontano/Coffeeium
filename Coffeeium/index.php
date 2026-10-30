<?php
session_start();

if (isset($_SESSION['email'])) {
    header('Location: main.php');
    exit();
} else {
    header('Location: mainrequire.php');
    exit();
}
?>