<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="cssrequire.css">
</head>
<body>
    <div id="login-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <p class="modal-message">
                Welcome to The Coffeeium De Resistance!
                <br>
                Please log in or register to view and add items to your order.
            </p>
            <div class="modal-actions">
                <a href="logout.php"><button class="modal-btn">Log In</button></a>
                <a href="logout .php"><button class="modal-btn">Register</button></a>
            </div>
            <p class="modal-hint">Please follow through :D.</p>
        </div>
    </div>
    <fieldset class="nameset">
        <nav class="header-nav">
            <div class="cname">
                <p class="shopname">The Coffeeium De Resistance</p>
                <h4>Crafted Coffee, Simple Pleasures.</h4>
            </div>
            
            <div class="nav-links">
                <a href="main.php"><button>Home</button></a>
                <a href="about.php"><button>About</button></a>
                <a href="order.php"><button>View Order</button></a>
                
                <div class="user-controls">
                    <a href="logout .php" id="user-btn"><button>???</button></a>
                </div>
            </div>
        </nav>
    </fieldset>
    
    <main>
        <div id="alert" class="receiver">
            <p> Item Added! You added an item to your cart.</p>
        </div>

        <fieldset>
            <legend>
                <h1 class="drinks">Our Specialties</h1>
            </legend>
            <hr>
            <table class="timage" id="specialties-table">
                <tr>
                    <th><a href="#" data-item-id="vanilla-latte"><img src="Vanillalatte.png"><br><p>Vanilla Latte</p><br>smooth espresso with creamy milk and vanilla sweetness</a></th>
                    <th><a href="#" data-item-id="Pistachio"> <img src="pistachio.png"><br><p>Pistachio </p><br>nutty, creamy latte with pistachio flavor</a></th>
                    <th><a href="#" data-item-id="Biscoff-Latte"> <img src="Biscoff.png"><br><p>Biscoff Latte </p><br>latte topped with whipped cream, biscoff crumbs</a></th>
                </tr>
                <tr>
                    <th><a href="#" data-item-id="Iced-Salted-Caramel-Mocha"><img src="icedsaltedcaramel.png"><br><p>Iced Salted Caramel Mocha</p><br> iced mocha with whipped cream and caramel drizzle</a></th>
                    <th><a href="#" data-item-id="Strawberry-Matcha"> <img src="strawberrymatcha.png"><br><p>Strawberry Matcha</p><br>earthy matcha mixed with sweet strawberry layers</a></th>
                    <th><a href="#" data-item-id="Iced-Dark-Mocha"> <img src="iceddarkmocha.png"><br><p>Iced Dark Mocha</p><br>bold espresso with deep, bittersweet chocolate, whipped cream and chocolate drizzle</a></th>
                </tr>
            </table>
            <hr>
        </fieldset>

        <fieldset>
            <legend>
                <h1><b>Pastry</b></h1>
            </legend>
            <hr>
            <table class="timage" id="pastries-table">
                <tr>
                    <th><a href="#" data-item-id="Garlic-Bread"> <img src="garlicbread.png"><br><p>Garlic Bread</p><br>toasted bread with buttery garlic flavor</a></th>
                    <th><a href="#" data-item-id="Chocolate-Cake"> <img src="chocolatecake.png"><br><p>Chocolate Cake</p><br>rich, moist cake with deep chocolate taste</a></th>
                    <th><a href="#" data-item-id="Croissant"> <img src="croissant.png"><br><p>Croissant</p><br>light, flaky, buttery pastry</a></th>
                </tr>
                <tr>
                    <th><a href="#" data-item-id="Lemon-Cake"><img src="lemoncake.png"><br><p>Lemon Cake</p><br> soft cake with a bright, citrusy lemon flavor</a></th>
                    <th><a href="#" data-item-id="Eclair"> <img src="eclair.png"><br><p>Eclair</p><br>Choux pastry filled with cream and topped with chocolate icing</a></th>
                    <th><a href="#" data-item-id="Scone"> <img src="scone.png"><br><p>Scone</p><br>Crumbly pastry, sometimes with berries or chocolate chips.</a></th>
                </tr>
            </table>
            <hr>
        </fieldset>
    </main>
    <script src="jsrequire.js"></script>
</body>
</html>