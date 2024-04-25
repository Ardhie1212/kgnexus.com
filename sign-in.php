<?php
session_start();
include './server/connection.php';

if (isset($_POST['submit-btn']) && isset($_POST['username']) && isset($_POST['passkey'])) {
    echo "Success1";
    $username = $_POST['username'];
    $password = $_POST['passkey'];

    // Prepare and execute SELECT query using prepared statement
    $query = "SELECT * FROM users WHERE username = ? AND passkey = ? LIMIT 1";
    $stmt_login = $conn->prepare($query);
    $stmt_login->bind_param('ss', $username, $password);

    if ($stmt_login->execute()) {
        echo "success2";
        // Bind result variables
        $stmt_login->bind_result($id_user, $email, $username, $stored_passkey, $alamat);
        $stmt_login->store_result();

        // Check if user exists
        if ($stmt_login->num_rows == 1) {
            echo "success3";
            $stmt_login->fetch();
            // Verify password using password_verify
            echo "Stored Passkey: " . $stored_passkey . "<br>";
            echo "Verify Result: " . (password_verify($password, $stored_passkey) ? 'true' : 'false') . "<br>";
            if (password_verify($password, $stored_passkey)) {
                echo "success4";
                // Set session variables
                $_SESSION['id_user'] = $id_user;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['passkey'] = $stored_passkey;
                $_SESSION['alamat'] = $alamat;
                $_SESSION['logged_in'] = true;

                // Redirect to homepage after successful login
                header('location: homepage.php?message=Logged in successfully');
                exit; // Make sure to exit after header redirect
            } else {
                // Incorrect password
                header('location: sign-in.php?error=Incorrect password');
                exit;
            }
        } else {
            // User not found
            header('location: sign-in.php?error=User not found');
            exit;
        }
    } else {
        // Database query error
        header('location: sign-in.php?error=Something went wrong');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignIn</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h1 id="title">Sign-In</h1>
            <form action="sign-in.php" method="POST">
                <div class="input-group">
                    <div class="input-field" id="usernameField">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" placeholder="username" name="username">
                    </div>

                    <div class="input-field">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" placeholder="Password" name="passkey">
                    </div>
                </div>
                <div class="btn-field">
                    <button type="button" id="signupBtn" class="disable">Sign Up</button>
                    <button type="button" id="signinBtn">Sign in</button>
                </div>
                <button>
                    <input type="submit" id="submit-btn" name="submit-btn">
                </button>
            </form>
        </div>
    </div>

    <script>
        let signupBtn = document.getElementById("signupBtn");
        let title = document.getElementById("title");

        signupBtn.onclick = function() {
            window.location.href = 'sign-up.php';
        }
    </script>
</body>

</html>