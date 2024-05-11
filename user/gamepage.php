<?php
include('../server/connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gamepage</title>
    <link rel="stylesheet" href="../style/gamepage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <a href="homepage.php">
                <h2>KGNEXUS</h2>
            </a>
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
                        <li><a href="">Action</a></li>
                        <li><a href="">Adventure</a></li>
                        <li><a href="">Role-playing</a></li>
                        <li><a href="">Simulation</a></li>
                        <li><a href="">Strategy</a></li>
                        <li><a href="">Sports & Racing</a></li>
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

    <!-- Game Banner -->
    <main>
        <div class="wrapper">
            <div class="game-banner">
                <!-- Game Banner Image and Content -->
                <img src="../images/game-images/header/header-balatro.jpg" alt="banner game balatro">
                <div class="game-content">
                    <!-- Game Information -->
                    <h1>Game Title</h1>
                    <p>Description of the game...</p>
                    <p>Category: Action</p>
                    <p>Company/Developer: ABC Games</p>
                    <p>Release Date: January 1, 2023</p>
                    <p>Size: 5 GB</p>
                </div>
            </div>
            <div class="game-media">
                <!-- Game Trailer -->
                <div class="video-container">
                    <video src="../images/game-images/video/video-balatro.webm" frameborder="0" ></video>
                </div>
                <!-- Game Photos -->
                <div class="image-gallery">
                    <img src="../images/game-images/photo1/photo1-balatro.jpg" alt="Game Photo 1">
                    <img src="../images/game-images/photo2/photo2-balatro.jpg" alt="Game Photo 2">
                    <img src="../images/game-images/photo3/photo3-balatro.jpg" alt="Game Photo 3">
                </div>
                <!-- Add to Cart Button -->
                <div class="add-to-cart">
                    <button>
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- End of Game Banner -->

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



</body>


</html>