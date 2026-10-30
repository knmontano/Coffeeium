<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <fieldset class="nameset">
        <nav class="header-nav">
            <div class="cname">
                <p class="shopname">The Coffeeium De Resistance</p>
                <h4>Crafted Coffee, Simple Pleasures.</h4>
            </div>
            
            <div class="nav-links">
                <a href="main.php"><button>Home</button></a>
                <a href="About.php"><button>About</button></a>
                <a href="order.php"><button>View Order</button></a>
                
                <div class="user-controls">
                  <a href="main.php"><button>User</button></a>
                  <a href="logout.php"><button class="logout-btn">Log Out</button></a>
                </div>
        </nav>
    </fieldset>
    
    <main>
        <div id="alert" class="receiver">
            <p> Item Added! You added an item to your cart.</p>
        </div>

        <fieldset>
            <legend>
                <h1 class="drinks">About our Website!</h1>
            </legend>
            <hr>
            <table class="timage">
                <tr>
                    <th><p>The Coffeeium De Resistance was founded on the simple belief that 
                        great coffee should be accessible to everyone. We source our beans 
                        ethically from small farms around the world and roast them in small 
                        batches right here in our shop. Our passion is to craft the perfect cup, 
                        whether you're starting your morning or winding down your day. Come enjoy 
                        a moment of peace with us.</p></th>
                </tr>
            </table>
            <hr>
        </fieldset>
    </main>
    <script src="main.js"></script>
</body>
</html>