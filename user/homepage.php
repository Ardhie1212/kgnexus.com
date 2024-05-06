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
    </main>
    <!-- End of Main content -->
    <!-- Footer -->
    <footer>
    </footer>
    <!-- End of footer -->
</body>
</html>