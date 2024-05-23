<?php
include('../server/connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id']) && isset($_POST['refund'])) {
    $transaction_id = $_POST['transaction_id'];
    $query_update = "UPDATE transaction SET Status='refund' WHERE transaction_id='$transaction_id'";
    $result = mysqli_query($conn, $query_update);

    if ($result) {
        // Redirect to the same page or any other page as per your requirement
        header("Location: ../user/history-transaction-user.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
