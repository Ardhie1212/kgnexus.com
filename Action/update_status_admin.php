<?php
include('../server/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $status = isset($_POST['status']) ? 'Verified' : 'waiting verified';

    $query_update = "UPDATE transaction SET status = '$status' WHERE transaction_id = $transaction_id";
    if (mysqli_query($conn, $query_update)) {
        // Redirect back to the table page
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
}
?>
