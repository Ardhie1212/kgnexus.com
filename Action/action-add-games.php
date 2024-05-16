<?php
include('../server/connection.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['add'])) {
    $game_name = $_POST['game_name'];
    $game_desc = $_POST['game_desc'];
    $game_category = $_POST['game_category'];
    $game_company = $_POST['game_company'];
    $size = $_POST['size'];
    $release_date = $_POST['release_date'];
    $rating = $_POST['rating'];
    $sector = $_POST['sector'];
    $game_price = $_POST['game_price'];

    // File uploads
    $header = $_FILES['header']['name'];
    $photo1 = $_FILES['photo1']['name'];
    $photo2 = $_FILES['photo2']['name'];
    $photo3 = $_FILES['photo3']['name'];
    $video = $_FILES['video']['name'];

    // Define the target directories for the uploads
    $target_dir = "uploads/";
    $header_target = $target_dir . basename($header);
    $photo1_target = $target_dir . basename($photo1);
    $photo2_target = $target_dir . basename($photo2);
    $photo3_target = $target_dir . basename($photo3);
    $video_target = $target_dir . basename($video);

    // Move the uploaded files to the target directory
    move_uploaded_file($_FILES['header']['tmp_name'], $header_target);
    move_uploaded_file($_FILES['photo1']['tmp_name'], $photo1_target);
    move_uploaded_file($_FILES['photo2']['tmp_name'], $photo2_target);
    move_uploaded_file($_FILES['photo3']['tmp_name'], $photo3_target);
    move_uploaded_file($_FILES['video']['tmp_name'], $video_target);

    // Insert query
    $sql = "INSERT INTO game (game_name, game_desc, game_category, game_company, size, release_date, rating, header, photo1, photo2, photo3, video, sector, game_price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if statement preparation was successful
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sssssssssssssi", $game_name, $game_desc, $game_category, $game_company, $size, $release_date, $rating, $header, $photo1, $photo2, $photo3, $video, $sector, $game_price);

    // Execute statement and check for errors
    if ($stmt->execute()) {
        $message = "insert_success";
    } else {
        $message = "error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close();

    header("Location: ../admin/dashboard-admin.php?message=$message");
    exit();
} else {
    echo "Form not submitted correctly.";
}
?>
