<?php
session_start();

include('server/connection.php');

if (isset($_SESSION['logged_in'])) {
	header('location: homepage.php');
	exit;
}

if (isset($_POST['submit-field'])) {
	$username = $_POST['username'];
	$passkey = $_POST['passkey'];

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

			header('location: homepage.php?message=Logged in successfully');
			exit;
		} else {
			header('location: login.php?error=Could not verify your account');
			exit;
		}
	} else {
		header('location: login.php?error=' . urlencode($conn->error));
		exit;
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div class="container">
		<div class="img">
			<img src="/images/Logo.jpg">
		</div>
		<div class="login-content">
			<form action="login.php" method="POST">
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
	<script>
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