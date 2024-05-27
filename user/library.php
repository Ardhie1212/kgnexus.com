<?php
include('../server/connection.php');
session_start();

// Check if session variables are set
if (isset($_SESSION['id_user']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
    $id_user = $_SESSION['id_user'];
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $passkey = $_SESSION['passkey'];
    $rekening = $_SESSION['rekening'];
    $saldo = $_SESSION['saldo'];

    // Now you can use these session variables as needed
} else {
    // Redirect to login page if session variables are not set
    header("Location: sign-up.php");
    exit();
}

if (isset($_GET['transaction_id'])) {
    $gameId = $_GET['game_id'];
    $check_query = "SELECT * FROM transaction WHERE game_id = $gameId AND id_user = $id_user";
    $check_result = mysqli_query($conn, $check_query);
}
// Fetch games associated with transactions of a specific user
$userId = $_SESSION['id_user']; // Assuming you have user ID in session
$query = "SELECT * FROM game
    JOIN transaction ON game.game_id = transaction.game_id
    JOIN user ON transaction.id_user = user.id_user
    WHERE user.id_user = $userId";

// Assuming $conn is 
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="../style/library.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="library.php" class="onpage">Library</a></li>
            <li><a href="mywallet.php">Wallet</a></li>
            <li><a href="shopping-cart.php">Cart</a></li>
        </ul>
        <i class='bx bxs-user-circle' id="user"></i>
        <div class="sub-menu-wrap" id="sub-menu-wrap">
            <a href="profile-user.php">Manage Account</a>
            <a href="history-transaction-user.php">History Transaction</a>
            <a href="sign-up.php" id="logout">Logout</a>
        </div>
    </nav>


    <!-- Main -->
    <main class="library">
        <h2>LIBRARY</h2>
        <div class="line"></div>
        <div class="library-container">
                <div class="card-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <!-- Game card -->
                        <div class="game-card">
                            <a href="gamepage.php?game_id=<?php echo $row['game_id'] ?>">
                                <div class="card-image">
                                    <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                                </div>
                                <div class="card-content">
                                    <h5><?php echo $row['game_name'] ?></h5>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
        <!-- End of Main -->


        <!-- Footer -->
        <section class="footer">
            <p>Designed by Kelompok 1</p>
            <p>Copyright © All rights reserved.</p>
        </section>
        <!-- End of Footer -->

        <!-- Javascript dropdown -->
        <script>
            document.getElementById('user').addEventListener('click', function() {
                document.getElementById('sub-menu-wrap').classList.toggle('sub-menu-show');
            });

            function confirmLogout() {
                modal.style.display = "block";
                centerModal();
            }

            function centerModal() {
                var modal = document.querySelector('.modal-content');
                modal.style.top = "50%";
                modal.style.left = "50%";
                modal.style.transform = "translate(-50%, -50%)";
            }

            window.addEventListener('resize', centerModal);

            var logoutBtn = document.getElementById("logout");
            var modal = document.querySelector('.modal-content');
            var closeModal = document.querySelector('.close');

            logoutBtn.addEventListener('click', function() {
                modal.style.display = "block";
                centerModal();
            });

            closeModal.addEventListener('click', function() {
                modal.style.display = "none";
            });

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });

            document.getElementById("confirmLogout").addEventListener("click", function() {
                window.location.href = "sign-up.php";
            });

            document.getElementById("cancelLogout").addEventListener("click", function() {
                modal.style.display = "none";
            });

            document.getElementById("logout").addEventListener('click', function(event) {
                event.preventDefault();
                confirmLogout();
            });
        </script>
        <!-- End of Javascript Logout Modal -->
</body>

</html>