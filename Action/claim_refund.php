<?php
// Mengatur tampilan kesalahan
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error_log.txt');
error_reporting(E_ALL);

include('../server/connection.php');
session_start();

$success = null;
$error_message = '';

// Memproses permintaan POST untuk klaim pengembalian dana
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim_refund'])) {
    $user_id = $_SESSION['id_user'] ?? 0;
    $transaction_id = filter_input(INPUT_POST, 'transaction_id', FILTER_VALIDATE_INT);

    // Memastikan Transaction ID valid
    if ($transaction_id) {
        $query_price_balance = "SELECT g.game_price, u.saldo 
                                FROM transaction t 
                                JOIN game g ON g.game_id = t.game_id 
                                JOIN user u ON u.id_user = t.id_user 
                                WHERE t.transaction_id=? AND t.id_user=?";
        $stmt = $conn->prepare($query_price_balance);
        $stmt->bind_param('ii', $transaction_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($game_price, $user_balance);
        $stmt->fetch();
        $stmt->close();

        // Memastikan Game Price dan User Balance valid
        if ($game_price !== null && $user_balance !== null) {
            $new_balance = $user_balance + $game_price;

            // Memperbarui saldo pengguna
            $query_update_balance = "UPDATE user SET saldo=? WHERE id_user=?";
            $stmt = $conn->prepare($query_update_balance);
            $stmt->bind_param('di', $new_balance, $user_id);
            if ($stmt->execute()) {
                $stmt->close();

                // Memperbarui status transaksi
                $query_update_status = "UPDATE transaction SET status='claimed' WHERE transaction_id=?";
                $stmt = $conn->prepare($query_update_status);
                $stmt->bind_param('i', $transaction_id);
                $success = $stmt->execute();
                $stmt->close();
            } else {
                // Penanganan kesalahan saat gagal memperbarui saldo
                $success = false;
                $error_message = 'Failed to update balance.';
                error_log('Failed to update balance for user_id: ' . $user_id);
            }
        } else {
            // Penanganan kesalahan saat Game Price atau User Balance tidak valid
            $success = false;
            $error_message = 'Invalid game price or user balance.';
            error_log('Invalid game price or user balance for transaction_id: ' . $transaction_id);
        }
    } else {
        // Penanganan kesalahan saat Transaction ID tidak valid
        $success = false;
        $error_message = 'Invalid transaction ID.';
        error_log('Invalid transaction ID: ' . $transaction_id);
    }

    // Mengirim respons JSON ke klien
    echo json_encode(['success' => $success, 'error' => $error_message]);
    exit;
}
?>