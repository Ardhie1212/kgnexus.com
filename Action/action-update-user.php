<?php
include '../server/connection.php';

if (isset($_POST['id_user'])) {
    $id_user = $_POST['id_user'];

    // Array to hold the update queries
    $updates = [];

    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        $updates[] = "email = '$email'";
    }

    if (!empty($_POST['username'])) {
        $username = $_POST['username'];
        $updates[] = "username = '$username'";
    }

    if (!empty($_POST['rekening'])) {
        $rekening = $_POST['rekening'];
        $updates[] = "rekening = '$rekening'";
    }

    // Check if there are updates to make
    if (!empty($updates)) {
        $query = "UPDATE user SET " . implode(', ', $updates) . " WHERE id_user = $id_user";

        if (mysqli_query($conn, $query)) {
            mysqli_close($conn);
            header('location: ../user/profile-user.php?success=Data telah diperbarui!');
            exit(); // Ensure to exit after redirecting
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        // Notify if no data was provided
        $error_message = "Tidak ada data yang diubah";
    }
} else {
    // Notify if id_user is not set
    $error_message = "ID pengguna tidak ditemukan";
}

// Redirect to profile page with error message
mysqli_close($conn);
header('location: ../user/profile-user.php?error=' . urlencode($error_message));
exit(); // Ensure to exit after redirecting
?>
