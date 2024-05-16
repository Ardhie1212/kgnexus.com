<?php
include('../server/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $game_name = $_POST['game_name'];
    $game_desc = $_POST['game_desc'];
    $game_category = $_POST['game_category'];
    $game_company = $_POST['game_company'];
    $size = $_POST['size'];
    $release_date = $_POST['release_date'];
    $rating = $_POST['rating'];
    $header = handleFileUpload('header', '../uploads/');
    $photo1 = handleFileUpload('photo1', '../uploads/');
    $photo2 = handleFileUpload('photo2', '../uploads/');
    $photo3 = handleFileUpload('photo3', '../uploads/');
    $video = handleFileUpload('video', '../uploads/');
    $sector = $_POST['sector'];
    $game_price = $_POST['game_price'];

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

function handleFileUpload($inputName, $uploadDir) {
    if (!empty($_FILES[$inputName]['name'])) {
        $targetFile = $uploadDir . basename($_FILES[$inputName]['name']);
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
            return $targetFile;
        } else {
            echo "Error uploading file: " . $_FILES[$inputName]['error'];
            return $_POST['existing_' . $inputName];
        }
    } else {
        return $_POST['existing_' . $inputName];
    }
}
?>
