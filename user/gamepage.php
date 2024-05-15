<?php
include('../server/connection.php');

$game_id = $_GET['game_id'];
$query_select = "SELECT * FROM game WHERE game_id = $game_id";
$result = mysqli_query($conn, $query_select);

if (mysqli_num_rows($result) == 1) {
    $game = mysqli_fetch_assoc($result);
    mysqli_close($conn);
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

                    <img src="../images/game-images/header/<?php echo $game['header'] ?>" alt="">
                    <div class="game-content">
                        <!-- Game Information -->
                        <h1><?php echo $game['game_name'] ?></h1>
                        <p><?php echo $game['game_desc'] ?></p>
                        <p>Category: <?php echo $game['game_category'] ?></p>
                        <p>Company/Developer: <?php echo $game['game_company'] ?></p>
                        <p>Release Date: <?php echo $game['release_date'] ?></p>
                        <p>Size: <?php echo $game['size'] ?>/p>
                    </div>
                </div>
                <div class="game-media">
                    <!-- Game Trailer -->
                    <div class="video-container">
                        <iframe src="../images/game-images/video/<?php echo $game['video'] ?>" frameborder="0"></iframe>
                    </div>
                    <!-- Game Photos -->
                    <div class="image-gallery">
                        <img src="../images/game-images/photo1/<?php echo $game['photo1'] ?>" alt="Game Photo 1">
                        <img src="../images/game-images/photo2/<?php echo $game['photo2'] ?> " alt="Game Photo 2">
                        <img src="../images/game-images/photo3/<?php echo $game['photo3'] ?>" alt="Game Photo 3">
                    </div>
                    <!-- Add to Cart Button -->
                    <div class="add-to-cart">
                        <h4>Rp. <?php echo $game['game_price'] ?></h4>
                        <div class="game-price-box">
                            <div class="inner-price-box">
                                <a href="shopping-cart.php?game_id=<?php echo $game_id ?>">
                                    <button>
                                        <i class="fas fa-shopping-cart"></i>
                                        Add to Cart
                                    </button>
                                </a>
                                <br>
                                <button>
                                    <i class="fas fa-shopping-cart"></i>
                                    BUY NOW
                                </button>
                            </div>
                        </div>
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

<?php } ?>