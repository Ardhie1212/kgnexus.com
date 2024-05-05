<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KGNexus</title>
    <link rel="stylesheet" href="../style/homepage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <i class='bx bx-menu' id="menu"></i>
            <div class="search-box">
                <i class='bx bx-search' id="search-icon"></i>
                <input type="search" placeholder="Search">
            </div>
            <ul class="links">
                <li><a href="#" id="Home">Home</a></li>
                <li><a href="#">Kategori</a></li>
                <li><a href="#">Wishlist</a></li>
                <li><a href="#">Cart</a></li>
            </ul>
            <i class='bx bxs-user-circle'></i>
        </nav>
    </header>
    <!-- End of Navigation Bar -->

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="#"><i class='bx bx-home-alt-2'></i>Menu 1</a></li>
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