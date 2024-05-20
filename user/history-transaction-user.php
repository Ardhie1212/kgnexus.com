<?php
include('../server/connection.php');
session_start();

$user_id = $_SESSION['id_user'] ?? $_GET['id_user'] ?? $_POST['id_user'] ?? 0;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refund'])) {
    $transaction_id = $_POST['transaction_id'];
    $query_update = "UPDATE transaction SET status='refund' WHERE transaction_id='$transaction_id'";
    if (mysqli_query($conn, $query_update)) {
        $success = true;
    } else {
        $success = false;
    }
}

$query_view = "SELECT t.transaction_id, g.header, g.game_name, g.game_price, u.email, u.username, t.status 
               FROM transaction t 
               JOIN game g ON g.game_id = t.game_id
               JOIN user u ON u.id_user = t.id_user 
               WHERE u.id_user = '$user_id'";

$result = mysqli_query($conn, $query_view);

if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Transaction</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/history-transaction.css">
</head>

<body>
    <div class="container">
        <button class="btn btn-primary mt-3" onclick="window.location.href='homepage.php'">
            <i class="bi bi-arrow-left-circle-fill"></i>
        </button>
        <h1 class="mt-3">History Transaction</h1>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Game Header</th>
                    <th>Game Name</th>
                    <th>Game Price</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td><img src="../images/game-images/header/<?php echo $row['header']; ?>" alt="<?php echo $row['game_name']; ?>" width="100"></td>
                            <td><?php echo $row['game_name']; ?></td>
                            <td><?php echo $row['game_price']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <?php if ($row['status'] != 'refund') { ?>
                                    <button class="btn btn-danger refund-btn" data-transaction-id="<?php echo $row['transaction_id']; ?>" data-toggle="modal" data-target="#refundModal<?php echo $row['transaction_id']; ?>">Refund</button>
                                <?php } else { ?>
                                    Refunded
                                <?php } ?>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="refundModal<?php echo $row['transaction_id']; ?>" tabindex="-1" aria-labelledby="refundModalLabel<?php echo $row['transaction_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="refundModalLabel<?php echo $row['transaction_id']; ?>">Refund Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to refund this transaction?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger confirm-refund" data-transaction-id="<?php echo $row['transaction_id']; ?>">Refund</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="8">No transactions found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".confirm-refund").click(function() {
                var transactionId = $(this).data('transaction-id');
                $.post("../Action/cancel-refund.php", {
                    transaction_id: transactionId
                }, function(data, status) {
                    if (status === 'success') {
                        location.reload();
                    } else {
                        console.error("Refund failed:", data);
                        alert("Refund failed. Please try again.");
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Refund request failed:", textStatus, errorThrown);
                    alert("Refund request failed. Please try again.");
                });
            });
        });
    </script>
</body>

</html>
