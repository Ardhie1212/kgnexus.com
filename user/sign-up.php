<?php
session_start();
include('../server/connection.php');

if (isset($_SESSION['logged_in'])) {
    header('location: homepage.php');
    exit;
}

if (isset($_POST['submit-btn'])) {
    if (strlen($_POST['email']) == 0 && strlen($_POST['rekening']) == 0) {
        $username = $_POST['username'];
        $passkey = $_POST['passkey'];
        $passkey = md5($passkey);
        $query = "SELECT * FROM user WHERE username = ? AND passkey = ? LIMIT 1";

        $stmt_login = $conn->prepare($query);
        $stmt_login->bind_param('ss', $username, $passkey);
        session_start();

        if ($stmt_login->execute()) {
            $stmt_login->bind_result($id_user, $email, $username, $passkey, $rekening, $saldo);
            $stmt_login->store_result();

            if ($stmt_login->num_rows() == 1) {
                $stmt_login->fetch();

                $_SESSION['id_user'] = $id_user;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['passkey'] = $passkey;
                $_SESSION['rekening'] = $rekening;
                $_SESSION['saldo'] = $saldo;
                header('location: homepage.php?login_success=1&username=' . urlencode($username));
                exit();
            } else {
                header('location: sign-up.php?error=invalid_credentials');
                exit();
            }
        } else {
            header('location: sign-up.php?error=something_went_wrong');
        }
    } else {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $passkey = $_POST['passkey'];
        $rekening = $_POST['rekening'];
        $saldo = 0;
        $hashedPassword = md5($passkey);

        $checkQuery = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            header('location: sign-up.php?error=username_taken');
            exit();
        } else {
            $query = "INSERT INTO user (email, username, passkey, rekening, saldo) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sssii", $email, $username, $hashedPassword, $rekening, $saldo);
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
                        <input type="email" placeholder="Email" name="email" required>
                    </div>
                    <div class="input-field" id="usernameField">
                        <i class='bx bxs-user'></i>
                        <input type="text" placeholder="Username" name="username" required>
                    </div>
                    <div class="input-field">
                        <i class='bx bxs-key'></i>
                        <input type="password" placeholder="Password" name="passkey" required>
                    </div>
                    <div class="input-field" id="rekeningField">
                        <i class='bx bxs-bank'></i>
                        <input type="number" placeholder="Bank account" name="rekening" required>
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

    <!-- Javascript Login Form Logic -->
    <script>
        let signinBtn = document.getElementById("signinBtn");
        let signupBtn = document.getElementById("signupBtn");
        let usernameField = document.getElementById("usernameField");
        let emailField = document.getElementById("emailField");
        let title = document.getElementById("title");
        let rekeningField = document.getElementById("rekeningField");
        let emailInput = document.getElementsByName("email")[0];
        let rekeningInput = document.getElementsByName("rekening")[0];

        signinBtn.onclick = function() {
            emailField.style.maxHeight = "0";
            rekeningField.style.maxHeight = "0";
            title.innerHTML = "Sign-In";
            signupBtn.classList.add("disable");
            signinBtn.classList.remove("disable");

            emailInput.removeAttribute("required");
            rekeningInput.removeAttribute("required");

            emailInput.value = "";
            rekeningInput.value = "";
        }

        signupBtn.onclick = function() {
            emailField.style.maxHeight = "60px";
            rekeningField.style.maxHeight = "60px";
            title.innerHTML = "Sign-Up";
            signupBtn.classList.remove("disable");
            signinBtn.classList.add("disable");

            emailInput.setAttribute("required");
            rekeningInput.setAttribute("required");
        }
    </script>
    <!-- End of Javascript Login Form Logic -->

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

    <!-- Invalid Credentials Modal -->
    <div id="invalidCredentialsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Invalid Credentials!</h2>
            <p>Username or password is incorrect!</p>
            <button id="invalidCredentialsOkButton">OK</button>
        </div>
    </div>
    <!-- End of Invalid Credentials Modal -->

    <!-- Username Taken Modal -->
    <div id="usernameTakenModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Username Taken!</h2>
            <p>The username you have entered is already taken. Please choose another one.</p>
            <button id="usernameTakenOkButton">OK</button>
        </div>
    </div>
    <!-- End of Username Taken Modal -->

    <!-- Data Error Modal -->
    <div id="dataErrorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Data Error!</h2>
            <p>There was an error with the data you entered, or your account is not registered.</p>
            <button id="dataErrorOkButton">OK</button>
        </div>
    </div>
    <!-- End of Data Error Modal -->

    <!-- Javascript Error Modal Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var successModal = document.getElementById("successModal");
            var errorModal = document.getElementById("errorModal");
            var invalidCredentialsModal = document.getElementById("invalidCredentialsModal");
            var usernameTakenModal = document.getElementById("usernameTakenModal");
            var dataErrorModal = document.getElementById("dataErrorModal");

            var closeButtons = document.getElementsByClassName("close");
            var modalOkButton = document.getElementById("modalOkButton");
            var errorOkButton = document.getElementById("errorOkButton");
            var invalidCredentialsOkButton = document.getElementById("invalidCredentialsOkButton");
            var usernameTakenOkButton = document.getElementById("usernameTakenOkButton");
            var dataErrorOkButton = document.getElementById("dataErrorOkButton");

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                successModal.style.display = "flex";
            } else if (urlParams.has('error')) {
                var errorType = urlParams.get('error');
                switch (errorType) {
                    case 'invalid_credentials':
                        invalidCredentialsModal.style.display = "flex";
                        break;
                    case 'username_taken':
                        usernameTakenModal.style.display = "flex";
                        break;
                    case 'something_went_wrong':
                        errorModal.style.display = "flex";
                        break;
                    case 'data_error':
                        dataErrorModal.style.display = "flex";
                        break;
                    default:
                        errorModal.style.display = "flex";
                }
            }

            for (let i = 0; i < closeButtons.length; i++) {
                closeButtons[i].onclick = function() {
                    successModal.style.display = "none";
                    errorModal.style.display = "none";
                    invalidCredentialsModal.style.display = "none";
                    usernameTakenModal.style.display = "none";
                    dataErrorModal.style.display = "none";
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

            invalidCredentialsOkButton.onclick = function() {
                invalidCredentialsModal.style.display = "none";
                window.location.href = window.location.pathname;
            }

            usernameTakenOkButton.onclick = function() {
                usernameTakenModal.style.display = "none";
                window.location.href = window.location.pathname;
            }

            dataErrorOkButton.onclick = function() {
                dataErrorModal.style.display = "none";
                window.location.href = window.location.pathname;
            }

            window.onclick = function(event) {
                if (event.target == successModal) {
                    successModal.style.display = "none";
                    window.location.href = window.location.pathname;
                } else if (event.target == errorModal) {
                    errorModal.style.display = "none";
                    window.location.href = window.location.pathname;
                } else if (event.target == invalidCredentialsModal) {
                    invalidCredentialsModal.style.display = "none";
                    window.location.href = window.location.pathname;
                } else if (event.target == usernameTakenModal) {
                    usernameTakenModal.style.display = "none";
                    window.location.href = window.location.pathname;
                } else if (event.target == dataErrorModal) {
                    dataErrorModal.style.display = "none";
                    window.location.href = window.location.pathname;
                }
            }

        });
    </script>
    <!-- End of Javascript Error Modal Logic -->

</body>

</html>