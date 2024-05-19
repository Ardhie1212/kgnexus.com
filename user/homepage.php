<?php
include('../server/banner_controller.php');
include('../server/connection.php');
include('../server/recommended_get.php');
include('../server/sale_get.php');
include('../server/most_played_get.php');
session_start();

$id_user = $_SESSION['id_user'];
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$passkey = $_SESSION['passkey'];
$rekening = $_SESSION['rekening'];
$saldo = $_SESSION['saldo'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/homepage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Homepage</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var searchInput = document.querySelector('input[type="search"]');
            searchInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Mencegah aksi default (hanya jika Anda ingin mengontrol form submission secara manual)
                    this.form.submit(); // Mengirimkan form secara manual
                }
            });
        });
    </script>
</head>

<body>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="" class="onpage">Home</a></li>
            <li><a href="">Library</a></li>
            <li><a href="mywallet.php">Wallet</a></li>
            <li><a href="shopping-cart.php">Cart</a></li>
        </ul>
        <i class='bx bxs-user-circle' id="user"></i>
        <div class="sub-menu-wrap" id="sub-menu-wrap">
            <a href="profile-user.php">Manage Account</a>
            <a href="sign-up.php" id="logout">Logout</a>
        </div>
    </nav>

    <!-- Javascript dropdown -->
    <script>
        document.getElementById('user').addEventListener('click', function() {
            document.getElementById('sub-menu-wrap').classList.toggle('sub-menu-show');
        });

        function confirmLogout() {
            modal.style.display = "block";
            centerModal();
        }
    </script>

    <!-- Logout Modal -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Are you sure you want to log out?</h2>
        <div>
            <button id="confirmLogout">Yes</button>
            <button id="cancelLogout">Cancel</button>
        </div>
    </div>
    <!-- End of Logout Modal -->

    <!-- Javascript Logout Modal -->
    <script>
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


    <header>
        <div class="header-content">
            <h2>KGNEXUS</h2>
            <section class="line"></section>
            <h1>Gateway to Epic Adventures</h1>
        </div>
    </header>

    <!-- Recommended Section -->
    <section class="recommended">
        <div class="title">
            <h1>Recommended For You</h1>
            <section class="line"></section>
        </div>
        <div class="content1">
            <div class="card-grid">
                <?php while ($row = $recommended->fetch_assoc()) { ?>
                    <div class="card1">
                        <a href="gamepage.php?game_id=<?php echo $row['game_id']; ?>">
                            <div class="card-image">
                                <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                            </div>
                            <div class="card-content">
                                <h5><?php echo $row['game_name'] ?></h5>
                                <p class="price">Rp. <?php echo number_format($row['game_price'], 2, ',', '.'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="search-game">
        <div class="search-content">
            <h1>SEARCH FOR GAME</h1>
            <section class="line"></section>
            <div class="search-box">
                <i class='bx bx-search' id="search-icon"></i>
                <form action="searchresult.php" class="search-form" method="POST">
                    <input type="search" placeholder="Search" name="search">
                </form>
            </div>
            <p>Or, pick your genre</p>
            <ul>
                <li><a href="categorypage.php?game_category=<?php echo 'Action' ?>">Action</a></li>
                <li><a href="categorypage.php?game_category=<?php echo 'Role-playing' ?>">Role-Playing</a></li>
                <li><a href="categorypage.php?game_category=<?php echo 'Strategy' ?>">Strategy</a></li>
                <li><a href="categorypage.php?game_category=<?php echo 'Sports' ?>">Sports & Racing</a></li>
                <li><a href="categorypage.php?game_category=<?php echo 'Simulation' ?>">Simulation</a></li>
                <li><a href="categorypage.php?game_category=<?php echo 'Adventure' ?>">Adventure</a></li>
            </ul>
        </div>
    </section>

    <!-- Most played -->
    <section class="mostplayed">
        <div class="title">
            <h1>Most Played Games</h1>
            <section class="line"></section>
        </div>
        <div class="content1">
            <div class="card-grid">
                <?php while ($row = $mostplayed->fetch_assoc()) { ?>
                    <div class="card1">
                        <a href="gamepage.php?game_id=<?php echo $row['game_id']; ?>">
                            <div class="card-image">
                                <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                            </div>
                            <div class="card-content">
                                <h5><?php echo $row['game_name'] ?></h5>
                                <p class="price">Rp. <?php echo number_format($row['game_price'], 2, ',', '.'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="sale">
        <div class="title">
            <h1>30% SALE</h1>
            <section class="line"></section>
        </div>
        <div class="content1">
            <div class="card-grid">
                <?php while ($row = $sale->fetch_assoc()) { ?>
                    <div class="card1">
                        <a href="gamepage.php?game_id=<?php echo $row['game_id']; ?>">
                            <div class="card-image">
                                <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                            </div>
                            <div class="card-content">
                                <h5><?php echo $row['game_name'] ?></h5>
                                <?php if (isset($row['price']) && $row['price'] < $row['game_price']) : ?>
                                    <p class="price"><s>Rp. <?php echo number_format($row['game_price'], 2, ',', '.'); ?></s></p>
                                    <p>Rp. <?php echo number_format($row['price'], 2, ',', '.'); ?></p>
                                <?php else : ?>
                                    <p class="price">Rp. <?php echo number_format($row['game_price'], 2, ',', '.'); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="footer">
        <p>Designed by Kelompok 1</p>
        <p>Copyright © All rights reserved.</p>
    </section>
</body>

</html>