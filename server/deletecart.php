<?php
include('../server/connection.php');

if (isset($_GET['game_id'])){
    $game_id = $_GET['game_id'];
    $query_delete = "DELETE FROM cart WHERE game_id = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param('i',$game_id);
    if ($stmt_delete->execute()){
        header('location: ../user/shopping-cart.php');
        exit;
    } else {
        echo 'Query execution failed.';
    }
    $stmt_delete->close();
} else {
    header('location: shoppingcart.php?error=Game_id is not set');
}

$conn->close();
?>