<?php
ob_start();

session_start();
require_once 'config.php'; 

$response = [
    'success' => false,
    'message' => 'An unexpected server error occurred.'
];

try {
    if (!isset($_SESSION['email']) || empty($_POST['cart_data'])) {
        throw new Exception('User not logged in or cart data is missing.', 1);
    }

    $cart_data = json_decode($_POST['cart_data'], true);
    $user_email = $_SESSION['email'];
    $total_amount = (float)$cart_data['totalPrice']; 
    $order_items = $cart_data['items']; 
    
    $unique_order_id = time() . mt_rand(100, 999);

    $stmt_item = $con->prepare("
        INSERT INTO orderss 
        (order_id, user_email, total_amount, status, product_name, totalPrice, quantity) 
        VALUES (?, ?, ?, 'Delivered', ?, ?, ?)
    ");
    if (!$stmt_item) {
        throw new Exception("Database prepare failed: " . $con->error, 5);
    }

    $order_placed = false;

    foreach ($order_items as $item_key => $item) {
        if (($item['quantity'] ?? 0) > 0) {
            $product_name = $item['name'] ?? $item_key; 
            $item_price = $item['price'] ?? 0;
            $item_quantity = $item['quantity'] ?? 0;

            
            $stmt_item->bind_param(
                "isdsdi", 
                $unique_order_id,      
                $user_email, 
                $total_amount, 
                $product_name, 
                $item_price,           
                $item_quantity         
            ); 

            if (!$stmt_item->execute()) {
                throw new Exception("MySQL Execute Error for item '{$product_name}': " . $stmt_item->error, 6);
            }
            $order_placed = true;
        }
    }
    $stmt_item->close();

    if (!$order_placed) {
        throw new Exception("No items were validly processed for the order.", 7);
    }
    
    $response = [
        'success' => true, 
        'message' => "Order items placed successfully! Transaction ID: #{$unique_order_id}. Your cart has been cleared.", 
        'order_id' => $unique_order_id
    ];

} catch (Exception $e) {
    $response['message'] = "Order failed: " . $e->getMessage();
}

ob_clean(); 
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>