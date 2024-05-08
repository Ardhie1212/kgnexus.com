<?php
include '../server/connection.php';

if (isset($_POST['id_user'], $_POST['email'], $_POST['username'], $_POST['alamat'])) {
    $id_user = $_POST['id_user'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE user SET email = '$email', username = '$username', alamat = '$alamat'
    WHERE id_user = $id_user";

    if (mysqli_query($conn, $query)) {
        header('location: ../user/profile.php?success=Data telah diperbarui!');
        exit(); // Pastikan untuk keluar dari skrip setelah mengalihkan
    } else {
        echo "Error: " . mysqli_error($conn); // Tampilkan pesan kesalahan jika kueri gagal
    }

    mysqli_close($conn);
} else {
    echo "Data kosong/tidak lengkap";
}
?>
