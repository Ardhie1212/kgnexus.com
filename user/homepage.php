<?php
include('../server/banner_controller.php');
include('../server/connection.php');
include('../server/recommended_get.php');
include('../server/sale_get.php');
include('../server/most_played_get.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KGNexus</title>
    <link rel="stylesheet" href="../style/homepage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <main>
        <section class="section">
            <div class="slider">
                <div class="slide">
                    <input type="radio" name="radio-btn" id="radio1">
                    <input type="radio" name="radio-btn" id="radio2">
                    <input type="radio" name="radio-btn" id="radio3">
                    <input type="radio" name="radio-btn" id="radio4">
                    <input type="radio" name="radio-btn" id="radio5">
                    <input type="radio" name="radio-btn" id="radio6">

                    <div class="st first">
                        <img src="../images/game-images/header/header-balatro.jpg" alt="">
                    </div>
                    <?php while ($row = $banner_image->fetch_assoc()) { ?>
                        <div class="st">
                            <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                        </div>
                    <?php } ?>

                    <div class="nav-auto">
                        <div class="a-b1"></div>
                        <div class="a-b2"></div>
                        <div class="a-b3"></div>
                        <div class="a-b4"></div>
                        <div class="a-b5"></div>
                        <div class="a-b6"></div>
                    </div>
                </div>

                <div class="nav-m">
                    <label for="radio1" class="m-btn"></label>
                    <label for="radio2" class="m-btn"></label>
                    <label for="radio3" class="m-btn"></label>
                    <label for="radio4" class="m-btn"></label>
                    <label for="radio5" class="m-btn"></label>
                    <label for="radio6" class="m-btn"></label>
                </div>
            </div>
        </section>

        <!-- Game content begin -->
        <div class="game1">
            <div class="btns">
                <i class='bx bx-caret-left' id="game_bx_1_left_btn"></i>
                <i class='bx bx-caret-right' id="game_bx_1_right_btn"></i>
            </div>
            <h3>Recommended For You</h3>
            <div class="game-bx" id="game_bx_1">
                <?php while ($row = $recommended->fetch_assoc()) { ?>
                    <a href="gamepage.php?game_id=<?= $row['game_id'] ?>">
                        <div class="card">
                            <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                            <div class="content">
                                <div class="left">
                                    <h5><?php echo $row['game_name'] ?></h5>
                                    <p><?php echo $row['rating'] ?></p>
                                </div>
                                <h6>IDR <?php echo $row['game_price'], 00 ?></h6>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="game2">
            <div class="btns">
                <i class='bx bx-caret-left' id="game_bx_2_left_btn"></i>
                <i class='bx bx-caret-right' id="game_bx_2_right_btn"></i>
            </div>
            <h3>SALE</h3>
            <div class="game-bx" id="game_bx_2">
                <?php while ($row = $sale->fetch_assoc()) { ?>
                    <a href="gamepage.php?game_id=<?= $row['game_id'] ?>">
                        <div class="card">
                            <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                            <div class="content">
                                <div class="left">
                                    <h5><?php echo $row['game_name'] ?></h5>
                                    <p><?php echo $row['rating'] ?></p>
                                </div>
                                <h6>IDR <?php echo $row['price'], 00 ?></h6>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="game3">
            <div class="btns">
                <i class='bx bx-caret-left' id="game_bx_3_left_btn"></i>
                <i class='bx bx-caret-right' id="game_bx_3_right_btn"></i>
            </div>
            <h3>Most Played</h3>
            <div class="game-bx" id="game_bx_3">
                <?php while ($row = $mostplayed->fetch_assoc()) { ?>
                    <a href="gamepage.php?game_id=<?= $row['game_id'] ?>">
                        <div class="card">
                            <img src="../images/game-images/header/<?php echo $row['header'] ?>" alt="">
                            <div class="content">
                                <div class="left">
                                    <h5><?php echo $row['game_name'] ?></h5>
                                    <p><?php echo $row['rating'] ?></p>
                                </div>
                                <h6>IDR <?php echo $row['game_price'], 00 ?></h6>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
    </main>
    <!-- End of Main content -->

    <!-- Javascript Slider -->
    <script type="text/javascript">
        var counter = 1;
        setInterval(function() {
            document.getElementById('radio' + counter).checked = true;
            counter++;
            if (counter > 6) {
                counter = 1;
            }
        }, 3000);
    </script>
    <!-- End of Javascript slider -->

    <!-- Javascript game card scroll -->
    <script>
        let game_bx_1 = document.getElementById('game_bx_1');
        let game_bx_1_left_btn = document.getElementById('game_bx_1_left_btn');
        let game_bx_1_right_btn = document.getElementById('game_bx_1_right_btn');

        let game_bx_2 = document.getElementById('game_bx_2');
        let game_bx_2_left_btn = document.getElementById('game_bx_2_left_btn');
        let game_bx_2_right_btn = document.getElementById('game_bx_2_right_btn');

        let game_bx_3 = document.getElementById('game_bx_3');
        let game_bx_3_left_btn = document.getElementById('game_bx_3_left_btn')
        let game_bx_3_right_btn = document.getElementById('game_bx_3_right_btn')


        game_bx_1_left_btn.addEventListener('click', () => {
            game_bx_1.scrollLeft -= 300;
        });

        game_bx_1_right_btn.addEventListener('click', () => {
            game_bx_1.scrollLeft += 300;
        });

        game_bx_2_left_btn.addEventListener('click', () => {
            game_bx_2.scrollLeft -= 300;
        });

        game_bx_2_right_btn.addEventListener('click', () => {
            game_bx_2.scrollLeft += 300;
        });

        game_bx_3_left_btn.addEventListener('click', () => {
            game_bx_3.scrollLeft -= 300;
        });

        game_bx_3_right_btn.addEventListener('click', () => {
            game_bx_3.scrollLeft += 300;
        });
    </script>
    <!-- End of Javascript game card scroll -->
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