<?php
include('../server/connection.php');
include('../server/sale_get.php');

if (isset($_GET['game_category'])) {
    $category = $_GET['game_category'];
    $query_get_game = "SELECT *, IF(Sector = 'Sale', game_price * 0.7, game_price) AS price FROM game WHERE game_category LIKE '%$category%'";
    $game = $conn->query($query_get_game);
}

if (isset($_GET['game_category'])) {
    $category = $_GET['game_category'];
    $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'asc';
    $query_get_game = "SELECT *, IF(Sector = 'Sale', game_price * 0.7, game_price) AS price 
                       FROM game 
                       WHERE game_category LIKE '%$category%' 
                       ORDER BY price $sorting";
    $game = $conn->query($query_get_game);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/categorypage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="library.php">Library</a></li>
            <li><a href="mywallet.php">Wallet</a></li>
            <li><a href="shopping-cart.php">Cart</a></li>
        </ul>
        <i class='bx bxs-user-circle' id="user"></i>
        <div class="sub-menu-wrap" id="sub-menu-wrap">
            <a href="profile-user.php">Manage Account</a>
            <a href="sign-up.php" id="logout">Sign out</a>
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
        <div class="icon">
            <i class='bx bx-message-alt-error'></i>
        </div>
        <h2>Confirm</h2>
        <p class="modal-title">Are you sure you want to Sign out?</p>
        <div>
            <button id="confirmLogout">Yes</button>
            <button id="cancelLogout">No</button>
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

    <section class="category-section">
        <div class="title">
            <h1>Category: <?php echo $category ?></h1>
            <section class="line"></section>
            <?php if (isset($_GET['game_category'])) { ?>
                <select id="sort-select">
                    <option value="asc">Lowest Price</option>
                    <option value="desc">Highest Price</option>
                </select>
            <?php } ?>
        </div>
        <div class="content1">
            <div class="card-grid">
                <?php while ($row = $game->fetch_assoc()) { ?>
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

    <script>
        // Mendengarkan perubahan pada elemen <select>
        document.addEventListener('DOMContentLoaded', function() {
            // Tangkap parameter kategori dan sorting dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('game_category');
            const sorting = urlParams.get('sort');

            // Setel nilai sorting pada dropdown
            const sortSelect = document.getElementById('sort-select');
            if (sorting) {
                sortSelect.value = sorting;
            }

            // Setel teks kategori berdasarkan nilai yang ditangkap dari URL
            const categoryHeading = document.getElementById('category-heading');
            if (categoryHeading) {
                categoryHeading.textContent = category || 'All Games'; // Jika tidak ada kategori yang dipilih, tampilkan "All Games"
            }
        });

        document.getElementById('sort-select').addEventListener('change', function() {
            var category = "<?php echo isset($_GET['game_category']) ? $_GET['game_category'] : ''; ?>";
            var sorting = this.value;
            var url = "categorypage.php?game_category=" + encodeURIComponent(category) + "&sort=" + sorting;
            window.location.href = url;
        });
    </script>
</body>

</html>