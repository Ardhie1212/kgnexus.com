<?php
include './server/connection.php';
session_start();




//sign-up algorythm
if (isset($_POST['submit-btn']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['passkey']) && isset($_POST['alamat'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $passkey = $_POST['passkey'];
    $alamat = $_POST['alamat'];

    // Hash the password using password_hash
    $hashedPassword = password_hash($passkey, PASSWORD_DEFAULT);

    // Prepare and execute SELECT query using prepared statement
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "Username or Email already exists. Please choose a different Username or Email.";
    } else {
        // Prepare and execute INSERT query using prepared statement
        $query = "INSERT INTO users (email, username, passkey, alamat) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $email, $username, $hashedPassword, $alamat);
        mysqli_stmt_execute($stmt);
        echo "Record inserted successfully!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h1 id="title">Sign-Up</h1>
            <form action="sign-up.php" method="POST">
                <div class="input-group">
                    <div class="input-field" id="emailField">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Email" name="email">
                    </div>

                    <div class="input-field" id="usernameField">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" placeholder="username" name="username">
                    </div>

                    <div class="input-field">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" placeholder="Password" name="passkey">
                    </div>

                    <div class="input-field" id="addressField">
                        <i class="fa-solid fa-map"></i>
                        <input type="text" placeholder="Alamat" name="alamat">
                    </div>
                </div>
                <div class="btn-field">
                    <button type="button" id="signupBtn">Sign up</button>
                    <button type="button" id="signinBtn" class="disable">Sign in</button>
                    
                </div>
                <button>
                    <input type="submit" id="submit-btn" name="submit-btn">
                </button>
            </form>
        </div>
    </div>

    <script>
        let signinBtn = document.getElementById("signinBtn");
        let signupBtn = document.getElementById("signupBtn");
        let usernameField = document.getElementById("usernameField");
        let emailField = document.getElementById("emailField");
        let title = document.getElementById("title");
        let addressField = document.getElementById("addressField");

        signinBtn.onclick = function() {
            window.location.href = "sign-in.php";
        }
    </script>
</body>

</html>