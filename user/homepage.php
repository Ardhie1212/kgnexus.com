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
            <h2 color="#eaeaea">KGNEXUS</h2>
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

    <!-- End of navigatrion -->

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
    </main>
    <!-- End of Main content -->
    <!-- Footer -->
    <footer>
    </footer>
    <!-- End of footer -->

        <script>
            const menuBtn = document.getElementById('menu');
            const sidebar = document.getElementById('sidebar');

            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show-sidebar');
                // Kalau div id="sidebar" punya class 'show-sidebar', maka class dihapus. Jika tidak, maka class 'show-sidebar' akan ditambahkan.
                menuBtn.classList.toggle('bx-x');
                // Kalau i dgn id="menu" punya class 'bx-x', class dihapus. Kalau ga punya, class nya ditambah. 
            });
        </script>
        <!-- End of JavaScript Sidebar -->
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
                        <input type="radio" name="radio-btn" id="radio7">
                        <input type="radio" name="radio-btn" id="radio8">
                        <input type="radio" name="radio-btn" id="radio9">
                        <input type="radio" name="radio-btn" id="radio10">

                        <div class="st first">
                            <img src="../images/Balatro.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/Battlefield1.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/Bellwright.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/Eldenring.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/Fallout4.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/Hades.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/iRacing.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/P3r.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/Stellaris.jpg" alt="">
                        </div>

                        <div class="st">
                            <img src="../images/Titanfall2.jpg" alt="">
                        </div>

                        <div class="nav-auto">
                            <div class="a-b1"></div>
                            <div class="a-b2"></div>
                            <div class="a-b3"></div>
                            <div class="a-b4"></div>
                            <div class="a-b5"></div>
                            <div class="a-b6"></div>
                            <div class="a-b7"></div>
                            <div class="a-b8"></div>
                            <div class="a-b9"></div>
                            <div class="a-b10"></div>
                        </div>
                    </div>

                    <div class="nav-m">
                        <label for="radio1" class="m-btn"></label>
                        <label for="radio2" class="m-btn"></label>
                        <label for="radio3" class="m-btn"></label>
                        <label for="radio4" class="m-btn"></label>
                        <label for="radio5" class="m-btn"></label>
                        <label for="radio6" class="m-btn"></label>
                        <label for="radio7" class="m-btn"></label>
                        <label for="radio8" class="m-btn"></label>
                        <label for="radio9" class="m-btn"></label>
                        <label for="radio10" class="m-btn"></label>
                    </div>
                </div>
            </section>
        </main>
        <script type="text/javascript">
            var counter = 1;
            setInterval(function(){
                document.getElementById('radio' + counter).checked=true;
                counter++;
                if(counter > 10){
                    counter = 1;
                }
            },5000);
        </script>

        <!-- End of Main content -->
        <!-- Footer -->
        <footer>
        </footer>
        <!-- End of footer -->
</body>

</html>