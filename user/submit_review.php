<?php
include('../server/connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game_id = $_POST['game_id'];
    $id_user = $_POST['id_user'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    // Prepared statement to insert review
    $stmt = $conn->prepare("INSERT INTO review (game_id, id_user, review, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $game_id, $id_user, $review, $rating);

    if ($stmt->execute()) {
        header("Location: gamepage.php?game_id=$game_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>