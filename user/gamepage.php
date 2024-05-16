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
        <style>
            header {
                width: 100vw;
                height: 100vh;
                background-image: linear-gradient(rgba(12, 0, 0, 0.55), rgba(12, 0, 0, 0.55)), url(../images/game-images/photo1/<?php echo $game['photo1'] ?>);
                background-position: bottom;
                background-size: cover;
                display: flex;
                align-items: center;
            }
        </style>
    </head>

    <body>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="">Library</a></li>
                <li><a href="">Wallet</a></li>
                <li><a href="shopping-cart.php">Cart</a></li>
            </ul>
            <i class='bx bxs-user-circle' id="user"></i>
            <div class="sub-menu-wrap" id="sub-menu-wrap">
                <a href="profile-user.php">Manage Account</a>
                <a href="sign-up.php" onclick='confirmLogout()'>Logout</a>
            </div>
        </nav>

        <header>
            <div class="header-content">
                <h2><?php echo $game['game_name'] ?></h2>
                <section class="line"></section>
                <p><?php echo $game['game_desc'] ?></p>
                <br>
                <br>
                <a href="shopping-cart.php?<?= $game['game_id'] ?>" class="addtocart">ADD TO CART</a>
                <p class="price">Rp. <?php echo $game['game_price'] ?></p>
            </div>
        </header>

        <section class="media">
            <div class="title">
                <h2>Overview</h2>
                <div class="line"></div>
            </div>
            <div class="media-container">
                <iframe src="../images/game-images/video/<?php echo $game['video'] ?>" frameborder="0"></iframe>
                <div class="image-wrapper">
                    <img src="../images/game-images/photo2/<?php echo $game['photo2'] ?>" alt="">
                </div>
                <div class="image-wrapper">
                    <img src="../images/game-images/photo3/<?php echo $game['photo3'] ?>" alt="">
                </div>
            </div>
        </section>

        <section class="player-ratings">
            <div class="title">
                <h2>Player's rating</h2>
                <div class="line"></div>
            </div>
            <div class="review-box">
            </div>
        </section>

        <section class="footer">
            <p>Designed by Kelompok 1</p>
            <p>Copyright © All rights reserved.</p>
        </section>
    </body>

    </html>
<?php } ?>