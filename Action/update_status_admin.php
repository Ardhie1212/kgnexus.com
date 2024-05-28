<?php
include('../server/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_refund'])) {
    $transaction_id = $_POST['transaction_id'];

    // Update the status to "Verified Refund"
    $query_update = "UPDATE transaction SET status = 'Verified Refund' WHERE transaction_id = $transaction_id";
    $result = mysqli_query($conn, $query_update);

    if ($result) {
        // Redirect to the same page or any other page as per your requirement
        header("Location: ../admin/list-transaction.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
