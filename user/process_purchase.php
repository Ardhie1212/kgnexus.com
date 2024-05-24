<?php

include('../server/connection.php');
session_start();

// Check if session variables are set
if (isset($_SESSION['id_user'], $_SESSION['saldo'], $_POST['subtotal'])) {
    
    $id_user = $_SESSION['id_user'];
    $saldo = $_SESSION['saldo'];
    $subtotal = $_POST['subtotal'];
    // Convert saldo and subtotal to float
    $saldo = floatval($_SESSION['saldo']);
    $subtotal = floatval($_POST['subtotal']);

     // Check if cart is empty
     $query_check_cart = "SELECT * FROM cart WHERE id_user = ?";
     $stmt_check_cart = $conn->prepare($query_check_cart);
     $stmt_check_cart->bind_param("i", $id_user);
     $stmt_check_cart->execute();
     $result_check_cart = $stmt_check_cart->get_result();
 
     if ($result_check_cart->num_rows === 0) {
         // Cart is empty
         echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
         exit(); // Stop further execution
     }

    // Check if saldo is sufficient for the purchase
    if ($saldo >= $subtotal) {
        //echo 'SALDO >= SUBTOTAL'; // This line is causing the issue, it should be removed or commented out
        // Query to get all items in the cart
        $query_cart = "SELECT * FROM cart WHERE id_user = ?";
        $stmt_cart = $conn->prepare($query_cart);
        $stmt_cart->bind_param("i", $id_user);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        if ($result_cart->num_rows > 0) {
            // Insert each item in the cart into the Transaction table
            while ($row = $result_cart->fetch_assoc()) {
                $game_id = $row['game_id'];
                $cart_id = $row['cart_id']; // Assuming there is a cart_id column

                $query_insert = "INSERT INTO Transaction (cart_id, game_id, id_user, status) VALUES (?, ?, ?, 'completed')";
                $stmt_insert = $conn->prepare($query_insert);
                $stmt_insert->bind_param("iii", $cart_id, $game_id, $id_user);
                $stmt_insert->execute();
            }

            // Delete all items from the cart for the user
            $query_delete_cart = "DELETE FROM cart WHERE id_user = ?";
            $stmt_delete_cart = $conn->prepare($query_delete_cart);
            $stmt_delete_cart->bind_param("i", $id_user);
            $stmt_delete_cart->execute();

            // Update user's saldo
            $new_saldo = $saldo - $subtotal;

            // Update user's saldo in the database
            $query_update_saldo = "UPDATE user SET saldo = ? WHERE id_user = ?";
            $stmt_update_saldo = $conn->prepare($query_update_saldo);
            $stmt_update_saldo->bind_param("di", $new_saldo, $id_user);
            $stmt_update_saldo->execute();

            // Update session saldo
            $_SESSION['saldo'] = $new_saldo;
            

            // Send JSON response
            echo json_encode(['status' => 'success', 'new_saldo' => $new_saldo]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insufficient balance']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in or invalid request']);
}

?>
