<?php
session_start();

include('../server/connection.php');

if (isset($_SESSION['logged_in'])) {
	header('location: dashboard-admin.php');
	exit;
}

if (isset($_POST['submit-field'])) {
	$username = $_POST['username'];
	$passkey = $_POST['passkey'];

	$query = "SELECT * FROM admin WHERE username = ? LIMIT 1";
	$stmt_login = $conn->prepare($query);
	$stmt_login->bind_param('s', $username);

	if ($stmt_login->execute()) {
		$stmt_login->bind_result($id_admin, $username_db, $passkey_db);
		$stmt_login->store_result();

		if ($stmt_login->num_rows() == 1) {
			$stmt_login->fetch();
			if ($passkey_db === $passkey) {
				$_SESSION['id_admin'] = $id_admin;
				$_SESSION['username'] = $username_db;
				$_SESSION['passkey'] = $passkey_db;

				header('location: dashboard-admin.php?message=Logged in successfully');
				exit;
			} else {
				header('location: login-admin.php?error=invalid_password');
				exit;
			}
		} else {
			header('location: login-admin.php?error=invalid_username');
			exit;
		}
	} else {
		header('location: login-admin.php?error=' . urlencode($conn->error));
		exit;
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="../style/login.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div class="container">
		<div class="login-content">
			<img src="../images/Logo.jpeg">
			<form action="login-admin.php" method="POST">
				<h2 class="title">Admin Login</h2>
				<div class="input-div one">
					<div class="i">
						<i class="fas fa-user"></i>
					</div>
					<div class="div">
						<h5>Username</h5>
						<input type="text" class="input" name="username" required>
					</div>
				</div>
				<div class="input-div pass">
					<div class="i">
						<i class="fas fa-lock"></i>
					</div>
					<div class="div">
						<h5>Password</h5>
						<input type="password" class="input" name="passkey" required>
					</div>
				</div>
				<input type="submit" class="btn" value="Login" name="submit-field">
			</form>
		</div>
	</div>

	<!-- Modals -->
	<div id="invalidUsernameModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2>Invalid Username</h2>
			<p>The username you have entered does not exist. Please try again.</p>
			<button id="invalidUsernameOkButton">OK</button>
		</div>
	</div>

	<div id="invalidPasswordModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2>Invalid Password</h2>
			<p>The password you have entered is incorrect. Please try again.</p>
			<button id="invalidPasswordOkButton">OK</button>
		</div>
	</div>

	<div id="dataErrorModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2>Data Error</h2>
			<p>There was an error with the data you entered. Please try again.</p>
			<button id="dataErrorOkButton">OK</button>
		</div>
	</div>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var invalidUsernameModal = document.getElementById("invalidUsernameModal");
			var invalidPasswordModal = document.getElementById("invalidPasswordModal");
			var dataErrorModal = document.getElementById("dataErrorModal");
			var closeButtons = document.getElementsByClassName("close");

			const urlParams = new URLSearchParams(window.location.search);
			if (urlParams.has('error')) {
				var errorType = urlParams.get('error');
				switch (errorType) {
					case 'invalid_username':
						invalidUsernameModal.style.display = "flex";
						break;
					case 'invalid_password':
						invalidPasswordModal.style.display = "flex";
						break;
					default:
						dataErrorModal.style.display = "flex";
				}
			}

			for (let i = 0; i < closeButtons.length; i++) {
				closeButtons[i].onclick = function() {
					invalidUsernameModal.style.display = "none";
					invalidPasswordModal.style.display = "none";
					dataErrorModal.style.display = "none";
					window.location.href = window.location.pathname;
				}
			}

			document.getElementById("invalidUsernameOkButton").onclick = function() {
				invalidUsernameModal.style.display = "none";
				window.location.href = window.location.pathname;
			}

			document.getElementById("invalidPasswordOkButton").onclick = function() {
				invalidPasswordModal.style.display = "none";
				window.location.href = window.location.pathname;
			}

			document.getElementById("dataErrorOkButton").onclick = function() {
				dataErrorModal.style.display = "none";
				window.location.href = window.location.pathname;
			}

			window.onclick = function(event) {
				if (event.target == invalidUsernameModal) {
					invalidUsernameModal.style.display = "none";
					window.location.href = window.location.pathname;
				} else if (event.target == invalidPasswordModal) {
					invalidPasswordModal.style.display = "none";
					window.location.href = window.location.pathname;
				} else if (event.target == dataErrorModal) {
					dataErrorModal.style.display = "none";
					window.location.href = window.location.pathname;
				}
			}
		});

		const inputs = document.querySelectorAll('.input');

		function focusFunc() {
			let parent = this.parentNode.parentNode;
			parent.classList.add('focus');
		}

		function blurFunc() {
			let parent = this.parentNode.parentNode;
			if (this.value == "") {
				parent.classList.remove('focus');
			}
		}

		inputs.forEach(input => {
			input.addEventListener('focus', focusFunc);
			input.addEventListener('blur', blurFunc);
		});
	</script>
</body>

</html>