<?php
include('../server/connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $game_name = $_POST['game_name'];
    $game_desc = $_POST['game_desc'];
    $game_category = $_POST['game_category'];
    $game_company = $_POST['game_company'];
    $size = $_POST['size'];
    $release_date = $_POST['release_date'];
    $rating = $_POST['rating'];
    $sector = $_POST['sector'];
    $game_price = $_POST['game_price'];

    // Handle file uploads
    $header = handleFileUpload('header', '../images/game-images/header/', $_POST['existing_header']);
    $photo1 = handleFileUpload('photo1', '../images/game-images/photo1/', $_POST['existing_photo1']);
    $photo2 = handleFileUpload('photo2', '../images/game-images/photo2/', $_POST['existing_photo2']);
    $photo3 = handleFileUpload('photo3', '../images/game-images/photo3/', $_POST['existing_photo3']);
    $video = handleFileUpload('video', '../images/game-images/video/', $_POST['existing_video']);

    $stmt = $conn->prepare("UPDATE game SET game_name=?, game_desc=?, game_category=?, game_company=?, size=?, release_date=?, rating=?, header=?, photo1=?, photo2=?, photo3=?, video=?, sector=?, game_price=? WHERE game_id=?");
    $stmt->bind_param("ssssssssssssssi", $game_name, $game_desc, $game_category, $game_company, $size, $release_date, $rating, $header, $photo1, $photo2, $photo3, $video, $sector, $game_price, $id);

    if ($stmt->execute()) {
        header("Location: ../admin/dashboard-admin.php?update_success=1");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

function handleFileUpload($inputName, $uploadDir, $existingFile)
{
    if (!empty($_FILES[$inputName]['name'])) {
        $targetFile = $uploadDir . basename($_FILES[$inputName]['name']);
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
            return basename($_FILES[$inputName]['name']);
        } else {
            echo "Error uploading file: " . $_FILES[$inputName]['error'];
            return $existingFile;
        }
    } else {
        return $existingFile;
    }
}
