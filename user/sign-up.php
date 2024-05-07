<?php

session_start();
include('../server/connection.php');

if (isset($_SESSION['logged_in'])) {
    header('location: homepage.php');
    exit;
}

if (isset($_POST['submit-btn'])){
    if (strlen($_POST['email']) == 0 && strlen($_POST['alamat']) == 0){
        $username = $_POST['username']; // Variabel $email menyimpan value dari <input> dengan name "user_email"
        $passkey = $_POST['passkey']; // Variabel $password menyimpan value dari <input> dengan name "user_password"
        $query = "SELECT * FROM user WHERE username = ? AND passkey = ? LIMIT 1";

        $stmt_login = $conn->prepare($query);
        $stmt_login->bind_param('ss',$username,$passkey);

        if($stmt_login->execute()){
            $stmt_login->bind_result($id_user, $email, $username, $passkey, $alamat);
            $stmt_login->store_result();

            if ($stmt_login->num_rows() == 1){
                $stmt_login->fetch();
                
                $_SESSION['id_user'] = $id_user;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['passkey'] = $passkey;
                $_SESSION['alamat'] = $alamat;

                header('location: homepage.php?message=Logged in successfully');
            } else {
                header('location: sign-up.php?error=Could not verify your account');
            }
        } else {
            header('location: sign-up.php?error=Something went wrong!');
        }
    } else {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $passkey = $_POST['passkey'];
        $alamat = $_POST['alamat'];

        $hashedPassword = password_hash($passkey, PASSWORD_DEFAULT);

        $checkQuery = "SELECT * FROM user WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Username or Email already exists. Please choose a different Username or Email.";
        } else {
            $query = "INSERT INTO user (email, username, passkey, alamat) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $email, $username, $hashedPassword, $alamat);
            mysqli_stmt_execute($stmt);
            echo "Record inserted successfully!";
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style/sign-up.css">
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h1 id="title">Sign-Up</h1>
            <form action="sign-up.php" method="POST">
                <div class="input-group">
                    <div class="input-field" id="emailField">
                        <i class='bx bx-envelope' ></i>
                        <input type="email" placeholder="Email" name="email">
                    </div>
                    <div class="input-field" id="usernameField">
                        <i class='bx bxs-user' ></i>    
                        <input type="text" placeholder="Username" name="username">
                    </div>

                    <div class="input-field">
                        <i class='bx bxs-key'></i>    
                        <input type="password" placeholder="Password" name="passkey">                        
                    </div>

                    <div class="input-field" id="addressField">
                        <i class='bx bx-current-location'></i>    
                        <input type="text" placeholder="Address" name="alamat">
                    </div>
                </div>
                <div class="btn-field">
                    <button type="button" id="signupBtn">Sign Up</button>
                    <button type="button" id="signinBtn" class="disable">Sign In</button>
                </div>
                <div class="submit-field">
                    <button type="submit" name="submit-btn" id="submit-btn">Submit</button>
                </div>
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
            let emailInput = document.getElementsByName("email");
            let addressInput = document.getElementsByName("alamat")


            signinBtn.onclick = function() {
                emailField.style.maxHeight = "0";
                addressField.style.maxHeight = "0";
                title.innerHTML = "Sign-In"
                signupBtn.classList.add("disable");
                signinBtn.classList.remove("disable");
            }

            document.getElementById("signinBtn").addEventListener("click", function() {
            // Mengambil elemen input untuk email dan alamat rumah
            var emailInput = document.getElementsByName("email")[0];
            var alamatInput = document.getElementsByName("alamat")[0];
            // Mengosongkan nilai input
            emailInput.value = "";
            alamatInput.value = "";
        });


        signupBtn.onclick = function() {
            emailField.style.maxHeight = "60px";
            title.innerHTML = "Sign-Up";
            addressField.style.maxHeight = "60px";
            signupBtn.classList.remove("disable");
            signinBtn.classList.add("disable");
        }
    </script>
</body>

</html>