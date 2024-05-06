<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KGNexus</title>
    <link rel="stylesheet" href="../style/homepage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<<<<<<< HEAD
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

=======
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
>>>>>>> 70ce003b0ddb66021ce1c32af432285ce01ed2dc
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
<<<<<<< HEAD
                <li><a href="#" id="Home">Your Store<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Category<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Wishlist<i class="fa fa-heart fa-sm" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Cart<i class="fa fa-shopping-cart" id="dropdown" aria-hidden="true"></i></a></li>
=======
                <li><a href="#">Store</a></li>
                <li><a href="#">Library</a></li>
                <li><a href="#">Category</a></li>
                <li><a href="#">Wishlist</a></li>
                <li><a href="#">Cart</a></li>
>>>>>>> 70ce003b0ddb66021ce1c32af432285ce01ed2dc
            </ul>
            <i class='bx bxs-user-circle'></i>
        </nav>
    </header>
    <!-- End of Navigation Bar -->

<<<<<<< HEAD
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="#">Menu 1</a></li>
            <li><a href="#">Menu 2</a></li>
            <li><a href="#">Menu 3</a></li>
            <li><a href="#">Menu 4</a></li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- JavaScript untuk Header dan Sidebar content -->
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

=======
>>>>>>> 70ce003b0ddb66021ce1c32af432285ce01ed2dc
    <!-- Main content -->
    <main>

    </main>
    <!-- End of Main content -->

    <!-- Footer -->
    <footer>

    </footer>
    <!-- End of footer -->
</body>

</html>