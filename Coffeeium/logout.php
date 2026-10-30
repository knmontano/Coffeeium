<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<body>
    <script>
        localStorage.removeItem('coffeeiumCart'); 
        window.location.href = 'login.php';
    </script>
</body>
</html>