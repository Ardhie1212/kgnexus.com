<?php
session_start();
include('../server/connection.php');

if (isset($_SESSION['logged_in'])) {
    header('location: homepage.php');
    exit;
}

if (isset($_POST['submit-btn'])) {
    if (strlen($_POST['email']) == 0 && strlen($_POST['alamat']) == 0) {
        $username = $_POST['username'];
        $passkey = $_POST['passkey'];
        $passkey = md5($passkey);
        $query = "SELECT * FROM user WHERE username = ? AND passkey = ? LIMIT 1";

        $stmt_login = $conn->prepare($query);
        $stmt_login->bind_param('ss', $username, $passkey);
        session_start();

        if ($stmt_login->execute()) {
            $stmt_login->bind_result($id_user, $email, $username, $passkey, $alamat);
            $stmt_login->store_result();

            if ($stmt_login->num_rows() == 1) {
                $stmt_login->fetch();

                $_SESSION['id_user'] = $id_user;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['passkey'] = $passkey;
                $_SESSION['alamat'] = $alamat;

                header('location: homepage.php?login_success=1&username=' . urlencode($username));
                exit();
            } else {
                header('location: sign-up.php?error=Could not verify your account');
                exit();
            }
        } else {
            header('location: sign-up.php?error=Something went wrong!');
        }
    } else {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $passkey = $_POST['passkey'];
        $alamat = $_POST['alamat'];

        $hashedPassword = md5($passkey);

        $checkQuery = "SELECT * FROM user WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            header('location: sign-up.php?error=1');
            exit();
        } else {
            $query = "INSERT INTO user (email, username, passkey, alamat) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $email, $username, $hashedPassword, $alamat);
            if (mysqli_stmt_execute($stmt)) {
                header('location: sign-up.php?success=1');
                exit();
            } else {
                header('location: sign-up.php?error=1');
                exit();
            }
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
                        <i class='bx bx-envelope'></i>
                        <input type="email" placeholder="Email" name="email">
                    </div>
                    <div class="input-field" id="usernameField">
                        <i class='bx bxs-user'></i>
                        <input type="text" placeholder="Username" name="username" required>
                    </div>
                    <div class="input-field">
                        <i class='bx bxs-key'></i>
                        <input type="password" placeholder="Password" name="passkey" required>
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

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Success!</h2>
            <p>You have successfully created an account!</p>
            <button id="modalOkButton">OK</button>
        </div>
    </div>
    <!-- End of Success Modal -->

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Error!</h2>
            <p>You failed to create an account!</p>
            <button id="errorOkButton">OK</button>
        </div>
    </div>
    <!-- End of Error Modal -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var successModal = document.getElementById("successModal");
            var errorModal = document.getElementById("errorModal");
            var closeButtons = document.getElementsByClassName("close");
            var modalOkButton = document.getElementById("modalOkButton");
            var errorOkButton = document.getElementById("errorOkButton");

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                successModal.style.display = "flex";
            } else if (urlParams.has('error')) {
                errorModal.style.display = "flex";
            }

            for (let i = 0; i < closeButtons.length; i++) {
                closeButtons[i].onclick = function() {
                    successModal.style.display = "none";
                    errorModal.style.display = "none";
                    window.location.href = window.location.pathname;
                }
            }

            modalOkButton.onclick = function() {
                successModal.style.display = "none";
                window.location.href = window.location.pathname;
            }

            errorOkButton.onclick = function() {
                errorModal.style.display = "none";
                window.location.href = window.location.pathname;
            }

            window.onclick = function(event) {
                if (event.target == successModal) {
                    successModal.style.display = "none";
                    window.location.href = window.location.pathname;
                } else if (event.target == errorModal) {
                    errorModal.style.display = "none";
                    window.location.href = window.location.pathname;
                }
            }

            let signinBtn = document.getElementById("signinBtn");
            let signupBtn = document.getElementById("signupBtn");
            let usernameField = document.getElementById("usernameField");
            let emailField = document.getElementById("emailField");
            let title = document.getElementById("title");
            let addressField = document.getElementById("addressField");
            let emailInput = document.getElementsByName("email")[0];
            let addressInput = document.getElementsByName("alamat")[0];

            signinBtn.onclick = function() {
                emailField.style.maxHeight = "0";
                addressField.style.maxHeight = "0";
                title.innerHTML = "Sign-In";
                signupBtn.classList.add("disable");
                signinBtn.classList.remove("disable");
                emailInput.value = "";
                addressInput.value = "";
            }

            signupBtn.onclick = function() {
                emailField.style.maxHeight = "60px";
                addressField.style.maxHeight = "60px";
                title.innerHTML = "Sign-Up";
                signupBtn.classList.remove("disable");
                signinBtn.classList.add("disable");
            }
        });
    </script>


</body>

</html>