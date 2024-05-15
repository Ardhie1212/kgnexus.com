<?php
include('../server/connection.php');
if (isset($_GET['game_category'])) {
    $category = $_GET['game_category'];
    $query_get_game = "SELECT * FROM game WHERE game_category = '$category'";
    $stmt_query = $conn->prepare($query_get_game);
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
                        <li><a href="categorypage.php?game_category=<?= 'Action' ?>">Action</a></li>
                        <li><a href="categorypage.php?game_category=<?= 'Adventure' ?>">Adventure</a></li>
                        <li><a href="categorypage.php?game_category=<?= 'Role-playing' ?>">Role-playing</a></li>
                        <li><a href="categorypage.php?game_category=<?= 'Simulator' ?>">Simulation</a></li>
                        <li><a href="categorypage.php?game_category=<?= 'Strategy' ?>">Strategy</a></li>
                        <li><a href="categorypage.php?game_category=<?= 'Sports' ?>">Sports & Racing</a></li>
                    </ul>

                </li>
                <li><a href="#">Wishlist<i class="" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Cart<i class="" id="dropdown" aria-hidden="true"></i></a></li>
            </ul>
            <i class='bx bxs-user-circle' id="user"></i>
            <div class="sub-menu-wrap" id="sub-menu-wrap">
                <a href="profile-user.php">Manage Account</a>
                <a href="sign-up.php">Logout</a>
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
    </script>
    <!-- End of javascript dropdown -->

    <main>
        <?php while ($row = $game->fetch_assoc()) {?>
        <p><?php echo $row['game_category'] ?>KOMTOOOL</p>
        <?php }?>
    </main>
</body>

</html>