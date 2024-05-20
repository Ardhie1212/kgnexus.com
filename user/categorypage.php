<?php
include('../server/connection.php');

if (isset($_GET['game_category'])) {
    $category = $_GET['game_category'];
    $query_get_game = "SELECT * FROM game WHERE game_category = ?";
    $stmt_query = $conn->prepare($query_get_game);
    $stmt_query->bind_param('s', $category);
    $stmt_query->execute();
    $game = $stmt_query->get_result();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/categorypage.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <h2>KGNEXUS</h2>
            <div class="search-box">
                <i class='bx bx-search' id="search-icon"></i>
                <input type="search" placeholder="Search">
            </div>
            <ul class="links">
                <li>
                    <a href="#" id="Home">Your Store<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a>
                    <ul class="dropyourstore" id="yourstoreclick">
                        <li><a href="#">Store</a></li>
                        <li><a href="#">Library</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#">Category<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a>
                    <ul class="genres" id="genres">
                        <li><a href="categorypage.php?game_category=<?= urlencode('Action') ?>">Action</a></li>
                        <li><a href="categorypage.php?game_category=<?= urlencode('Adventure') ?>">Adventure</a></li>
                        <li><a href="categorypage.php?game_category=<?= urlencode('Role-playing') ?>">Role-playing</a></li>
                        <li><a href="categorypage.php?game_category=<?= urlencode('Simulation') ?>">Simulation</a></li>
                        <li><a href="categorypage.php?game_category=<?= urlencode('Strategy') ?>">Strategy</a></li>
                        <li><a href="categorypage.php?game_category=<?= urlencode('Sports & Racing') ?>">Sports & Racing</a></li>
                    </ul>

                </li>
                <li><a href="#">Wishlist<i class="" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Cart<i class="" id="dropdown" aria-hidden="true"></i></a></li>
            </ul>
            <i class='bx bxs-user-circle' id="user"></i>
            <div class="sub-menu-wrap" id="sub-menu-wrap">
                <a href="profile-user.php?id=<?php echo $_SESSION['id_user']; ?>">Manage Account</a>
                <a href="sign-up.php" onclick='confirmLogout()'>Logout</a>
            </div>
        </nav>
    </header>

    <!-- End of navigation bar -->

    <!-- Javascript Dropdown -->
    <script>
        let yourStore = document.getElementById('yourstoreclick');
        let genres = document.getElementById('genres');
        let special = document.getElementById('special')

        document.getElementById('user').addEventListener('click', function() {
            document.getElementById('sub-menu-wrap').classList.toggle('sub-menu-show');
        });

        user.addEventListener('click', () => {
            dropUser.classList.toggle('user-details-show');
        });

        yourStore.previousElementSibling.addEventListener('click', () => {
            yourStore.classList.toggle('dropyourstore-show');
        });

        genres.previousElementSibling.addEventListener('click', () => {
            genres.classList.toggle('genres-show');
        });

        special.previousElementSibling.addEventListener('click', () => {
            genres.classList.toggle('special-show');
        });

        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = 'sign-up.php';
            }
        }
    </script>
    <!-- End of javascript dropdown -->

    <!-- Main content -->

    <main class="content">
        <h1 id="category-heading"></h1>
        <div class="card-grid">
            <?php while ($row = $game->fetch_assoc()) { ?>
                <div class="card">
                    <a href="gamepage.php?game_id=<?php echo $row['game_id']; ?>">
                        <div class="card-image">
                            <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                        </div>
                        <div class="card-content">
                            <h5><?php echo $row['game_name'] ?></h5>
                            <p><strong>Size:</strong> <?php echo $row['size'] ?></p>
                            <p><strong>Rating:</strong> <?php echo $row['rating'] ?></p>
                            <p>IDR <?php echo $row['game_price'], 00 ?></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </main>


    <!-- End of Main content -->

    <!-- Footer -->
    <footer id="footer" class="show-footer">
        <div class="footer-container">
            <div class="logo">
                <img src="../images/TransparentLogo.png" alt="KGNexus Logo">
            </div>
            <div class="copyright">
                <p>Copyright &copy;2024; Designed by <span class="designer">KGNexus Team</span></p>
            </div>
        </div>
    </footer>
    <!-- End of footer -->

    <!-- Javascript Footer -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var footer = document.getElementById('footer');
            var windowHeight = window.innerHeight;
            var fullHeight = document.documentElement.scrollHeight;
            var footerHeight = footer.offsetHeight;

            function toggleFooter() {
                var scrollPosition = window.scrollY;
                if (scrollPosition + windowHeight >= fullHeight - footerHeight) {
                    footer.classList.add('show-footer');
                } else {
                    footer.classList.remove('show-footer');
                }
            }

            toggleFooter();
            document.addEventListener('scroll', toggleFooter);
            window.addEventListener('resize', toggleFooter);
        });
    </script>
    <!-- End of Javascript Footer -->
</body>

</html>