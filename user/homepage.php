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
                <li><a href="#" id="Home">Your Store<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Category<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Wishlist<i class="fa fa-heart fa-sm" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Cart<i class="fa fa-shopping-cart" id="dropdown" aria-hidden="true"></i></a></li>
            </ul>
            <i class='bx bxs-user-circle'></i>
        </nav>
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