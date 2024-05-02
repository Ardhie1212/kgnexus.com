<?php
if (isset($_POST['submit-field'])) {
    $username = $_POST['username'];
    $passkey = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username = ? AND passkey = ? LIMIT 1";
    $stmt_login = $conn->prepare($query);
    $stmt_login->bind_param('ss', $username, $passkey);

    if ($stmt_login->execute()) {
        $stmt_login->bind_result($id_admin, $username, $passkey);
        $stmt_login->store_result();

        if ($stmt_login->num_rows() == 1) {
            $stmt_login->fetch();

            $_SESSION['id_admin'] = $id_admin;
            $_SESSION['username'] = $username;
            $_SESSION['passkey'] = $passkey;

            header('location: adminpage.php?message=Logged in successfully');
        } else {
            header('location: login.php?error=Could not verify your account');
        }
    } else {
        header('location: login.php?error=Something went wrong!');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="form-box">
        <form action="login.php" method="post">
            <div class="input-field">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="submmit-field">
                <input type="submit" name="login" id="login">
            </div>
        </form>
    </div>
</body>

</html>