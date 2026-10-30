<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

$user_email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Summary</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="order.css">
    <style>
        .order-container { max-width: 900px; margin: 20px auto; padding: 20px; }
        .order-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .order-table th, .order-table td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .qty { text-align: center; }
        .item-actions button { padding: 5px 10px; margin-left: 5px; cursor: pointer; }
        .past-order-box { border: 1px solid #eee; padding: 15px; margin-top: 15px; border-radius: 5px; }
        .final-total { font-weight: bold; font-size: 1.2em; }
        .checkout-btn { 
            width: 100%; padding: 15px; background-color: #4CAF50; color: white; 
            border: none; cursor: pointer; font-size: 1.1em; margin-top: 20px; 
        }
        .empty-cart-message { text-align: center; padding: 30px; }
    </style>
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
                <a href="about.php"><button>About</button></a>
                <a href="order.php"><button>View Order</button></a>
                <div class="user-controls">
                    <a href="main.php"><button>User</button></a>
                    <a href="logout.php"><button class="logout-btn">Log Out</button></a>
                </div>
            </div>
        </nav>
    </fieldset>

    <div class="order-container">
        <h1>🧾 Your Current Order </h1>
        <hr>

        <div id="order-items-list" class="receipt">
            <p class="loading-message">Loading your items...</p>
        </div>

        <div id="order-summary" class="summary-card">
            <div class="summary-line">
                <span>Total Items:</span>
                <span id="summary-count">0</span>
            </div>
            <div class="summary-line final-total">
                <span>TOTAL PRICE:</span>
                <span id="summary-price">₱0.00</span>
            </div>
            <hr class="summary-hr">
        </div>
        
        <button id="place-order-btn" class="checkout-btn" onclick="checkout()">Place Order</button>

        <a href="main.php" class="back-link">
            <button class="menu-btn">← Back to Menu for More Items</button>
        </a>

        <hr style="margin-top: 30px;">
        
        <h2>Past Orders History</h2>
        <div id="past-orders-list">
        <?php
if ($con->connect_error) {
    echo "<p style='color:red; font-weight:bold;'>Database Connection Error: " . htmlspecialchars($con->connect_error) . "</p>";
} else {
    // We select the overall order details (grouping by the transaction ID)
    // We use GROUP_CONCAT to get a list of all item names/quantities for display
    $sql = "
        SELECT 
            order_id, 
            MAX(total_amount) as total_amount, 
            MAX(status) as status,
            GROUP_CONCAT(CONCAT(quantity, 'x ', product_name) SEPARATOR '<br>') as item_list
        FROM orderss 
        WHERE user_email = ? 
        GROUP BY order_id 
        ORDER BY order_id DESC
    ";
    
    if ($stmt_orders = $con->prepare($sql)) {
        $stmt_orders->bind_param("s", $user_email);
        $stmt_orders->execute();
        $result_orders = $stmt_orders->get_result();

        if ($result_orders->num_rows > 0) {
            while($order = $result_orders->fetch_assoc()) {
                echo "<div class='past-order-box'>";
                echo "<h3>Order #" . htmlspecialchars($order['order_id']) . "</h3>";
                echo "<p>Total: ₱" . number_format($order['total_amount'], 2) . "</p>";
                echo "<p>Status: <b>" . htmlspecialchars($order['status']) . "</b></p>";
                
                // Display items using the concatenated list
                echo "<ul>";
                echo "<li>" . $order['item_list'] . "</li>";
                echo "</ul>";

                echo "</div>";
            }
        } else {
            echo "<p>You have no past orders.</p>";
        }
        $stmt_orders->close();
    } else {
        // This is the error message that was printing before; it should now be fixed.
        echo "<p style='color:red; font-weight:bold;'>Query Preparation Error: " . htmlspecialchars($con->error) . "</p>";
    }
}
?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const PRICES = {
                'vanilla-latte': 145, 'Pistachio': 165, 'Biscoff-Latte': 175,
                'Iced-Salted-Caramel-Mocha': 165, 'Strawberry-Matcha': 175, 'Iced-Dark-Mocha': 180,
                'Garlic-Bread': 65, 'Chocolate-Cake': 120, 'Croissant': 75,
                'Lemon-Cake': 110, 'Eclair': 95, 'Scone': 85,
            };

            const orderItemsList = document.getElementById('order-items-list');
            const summaryCount = document.getElementById('summary-count');
            const summaryPrice = document.getElementById('summary-price');
            const placeOrderBtn = document.getElementById('place-order-btn'); 

            window.cart = loadCartFromStorage();

            function loadCartFromStorage() {
                const storedCart = localStorage.getItem('coffeeiumCart');
                if (storedCart) {
                    try {
                        return JSON.parse(storedCart);
                    } catch (e) {
                        console.error("Error parsing cart from storage, starting new cart.", e);
                    }
                }
                return { items: {}, totalPrice: 0, totalCount: 0 };
            }

            function saveCartToStorage() {
                localStorage.setItem('coffeeiumCart', JSON.stringify(window.cart));
            }

            const formatPrice = (price) => {
                return price.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
            };
            
            function calculateTotals() {
                let newCount = 0;
                let newPrice = 0;

                for (const itemId in window.cart.items) {
                    const item = window.cart.items[itemId];
                    if (item.quantity > 0) {
                        newCount += item.quantity;
                        newPrice += item.quantity * item.price;
                    }
                }
                window.cart.totalCount = newCount;
                window.cart.totalPrice = newPrice;
            }

            function renderOrder() {
                orderItemsList.innerHTML = ''; 
                
                if (window.cart.totalCount === 0) {
                    orderItemsList.innerHTML = '<p class="empty-cart-message">Your cart is empty! Time to order some coffee.</p>';
                    summaryCount.textContent = 0;
                    summaryPrice.textContent = formatPrice(0);
                    placeOrderBtn.style.display = 'none';
                    saveCartToStorage();
                    return;
                }

                placeOrderBtn.style.display = 'block'; 

                const table = document.createElement('table');
                table.classList.add('order-table');
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th style="text-align: center;">Qty</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                `;
                const tbody = table.querySelector('tbody');

                for (const itemId in window.cart.items) {
                    const item = window.cart.items[itemId];
                    if (item.quantity > 0) {
                        const subtotal = item.quantity * item.price;
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.name}</td>
                            <td class="qty">${item.quantity}</td>
                            <td>${formatPrice(item.price)}</td>
                            <td>${formatPrice(subtotal)}</td>
                            <td style="text-align: right;">
                                <div class="item-actions">
                                    <button class="add-btn" data-id="${itemId}">+</button>
                                    <button class="deduct-btn" data-id="${itemId}">-</button>
                                    <button class="remove-btn" data-id="${itemId}">Remove</button>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(row);
                    }
                }
                orderItemsList.appendChild(table);
                summaryCount.textContent = window.cart.totalCount;
                summaryPrice.textContent = formatPrice(window.cart.totalPrice);
                document.querySelectorAll('.add-btn').forEach(btn => {
                    btn.addEventListener('click', handleCartAction);
                });
                document.querySelectorAll('.deduct-btn').forEach(btn => {
                    btn.addEventListener('click', handleCartAction);
                });
                document.querySelectorAll('.remove-btn').forEach(btn => {
                    btn.addEventListener('click', handleCartAction);
                });
            }

            function handleCartAction(event) {
                const itemId = event.currentTarget.getAttribute('data-id');
                
                let actionType;
                if (event.currentTarget.classList.contains('add-btn')) {
                    actionType = 'add';
                } else if (event.currentTarget.classList.contains('deduct-btn')) {
                    actionType = 'deduct';
                } else {
                    actionType = 'remove';
                }
                
                if (!window.cart.items[itemId]) return;

                const item = window.cart.items[itemId];
                
                if (actionType === 'add') {
                    item.quantity++; 
                } else if (actionType === 'deduct') {
                    item.quantity--; 
                    
                    if (item.quantity <= 0) {
                        delete window.cart.items[itemId];
                    }
                } else if (actionType === 'remove') {
                    delete window.cart.items[itemId];
                }
                calculateTotals();
                saveCartToStorage();
                renderOrder();
            }

            window.checkout = function() {
                if (window.cart.totalCount === 0) {
                    alert("Your cart is empty and cannot be processed.");
                    return;
                }

                if (!confirm(`Confirm placing an order for ${formatPrice(window.cart.totalPrice)}?`)) {
                    return;
                }

                const formData = new FormData();
                formData.append('cart_data', JSON.stringify(window.cart));

                fetch('process_order.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        localStorage.removeItem('coffeeiumCart');
                        window.location.reload(); 
                    } else {
                        alert('Order failed: ' + data.message);
                        console.error('Server error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error during checkout:', error);
                    alert('An unexpected error occurred during checkout. Please check the console for details.');
                });
            };

            renderOrder();
        });
    </script>
</body>
</html>