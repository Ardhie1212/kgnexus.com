<?php
include('../server/connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
    $query_update = "UPDATE transaction SET status='refund' WHERE transaction_id='$transaction_id'";
    if (mysqli_query($conn, $query_update)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Invalid request";
}
?>
