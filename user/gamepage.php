<?php
include('../server/connection.php');
session_start();
$id_user = $_SESSION['id_user'];

$game_id = $_GET['game_id'];

// Menggunakan prepared statements untuk keamanan
$stmt_game = $conn->prepare("SELECT * FROM game WHERE game_id = ?");
$stmt_game->bind_param("i", $game_id);
$stmt_game->execute();
$result_game = $stmt_game->get_result();

$stmt_transaction = $conn->prepare("SELECT * FROM transaction WHERE game_id = ? AND id_user = ?");
$stmt_transaction->bind_param("ii", $game_id, $id_user);
$stmt_transaction->execute();
$result_transaction = $stmt_transaction->get_result();


$stmt_transaction = $conn->prepare("SELECT * FROM transaction WHERE game_id = ? AND id_user = ?");
$stmt_transaction->bind_param("ii", $game_id, $id_user);
$stmt_transaction->execute();
$result_transaction = $stmt_transaction->get_result();

$stmt_reviews = $conn->prepare("SELECT review.*, user.username FROM review JOIN user ON review.id_user = user.id_user WHERE game_id = ?");
$stmt_reviews->bind_param("i", $game_id);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();


if ($result_game->num_rows == 1) {
    $game = $result_game->fetch_assoc();

    // Nilai default untuk tombol
    $button_text = "ADD TO CART";
    $button_href = "shopping-cart.php?game_id=" . $game['game_id'];

    // Ganti nilai jika transaksi ditemukan
    if ($result_transaction->num_rows >= 1) {
        $button_text = "INSTALL";
        $button_href = "#";
    }

    $stmt_game->close();
    $stmt_transaction->close();
    $conn->close();
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
                background-image: linear-gradient(rgba(12, 0, 0, 0.55), rgba(12, 0, 0, 0.55)), url(../images/game-images/photo1/<?php echo htmlspecialchars($game['photo1']); ?>);
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
                <li><a href="library.php">Library</a></li>
                <li><a href="mywallet.php">Wallet</a></li>
                <li><a href="shopping-cart.php">Cart</a></li>
            </ul>
            <i class='bx bxs-user-circle' id="user"></i>
            <div class="sub-menu-wrap" id="sub-menu-wrap">
                <a href="profile-user.php">Manage Account</a>
                <a href="history-transaction-user.php">History Transaction</a>
                <a href="sign-up.php" onclick='confirmLogout()'>Logout</a>
            </div>
        </nav>

        <header>
            <div class="header-content">
                <h2><?php echo htmlspecialchars($game['game_name']); ?></h2>
                <p><?php echo htmlspecialchars($game['game_company']); ?></p>
                <br>
                <p>Release: <?php echo $game['release_date']?></p>
                <section class="line"></section>
                <p><?php echo htmlspecialchars($game['game_desc']); ?></p>
                
                <br>
                <br>
                <a href="<?php echo htmlspecialchars($button_href); ?>" class="addtocart"><?php echo htmlspecialchars($button_text); ?></a>
                <?php if ($result_transaction->num_rows >= 1) : ?>
                    <p class="price"><?php echo htmlspecialchars($game['size'])?></p>
                <?php else : ?>
                    <p class="price">Rp. <?php echo number_format($game['game_price'], 2, ',', '.'); ?></p>
                <?php endif; ?>
            </div>
        </header>

        <section class="media">
            <div class="title">
                <h2>Overview</h2>
                <div class="line"></div>
            </div>
            <div class="media-container">
                <iframe src="../images/game-images/video/<?php echo htmlspecialchars($game['video']); ?>" frameborder="0"></iframe>
                <div class="image-wrapper">
                    <img src="../images/game-images/photo2/<?php echo htmlspecialchars($game['photo2']); ?>" alt="">
                </div>
                <div class="image-wrapper">
                    <img src="../images/game-images/photo3/<?php echo htmlspecialchars($game['photo3']); ?>" alt="">
                </div>
            </div>
        </section>

        <section class="player-ratings">
            <div class="title">
                <h2>Player's rating</h2>
                <div class="line"></div>
            </div>
            <div class="review-box">
                <?php if ($result_transaction->num_rows >= 1) : ?>
                    <div class="write-review">
                        <p>Write your review</p>
                        <form action="submit_review.php" method="POST">
                            <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
                            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                            <textarea name="review" required placeholder="Write your review here..."></textarea>
                            <input type="number" name="rating" min="1" max="5" required placeholder="Rating (1-5)">
                            <button type="submit"><i class='bx bx-paper-plane'></i> Send</button>
                        </form>
                    </div>
                <?php endif; ?>
                <div class="reviews-list">
                    <?php while ($review = $result_reviews->fetch_assoc()) : ?>
                        <div class="review-item">
                            <i class='bx bxs-user-circle' id="Profile-picture"></i>
                            <div class="review-content">
                                <p class="username"><?php echo htmlspecialchars($review['username']); ?></p>
                                <p class="review-text"><?php echo htmlspecialchars($review['review']); ?></p>
                                <p class="rating"><?php echo htmlspecialchars($review['rating']); ?> / 5</p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>


        <section class="footer">
            <p>Designed by Kelompok 1</p>
            <p>Copyright © All rights reserved.</p>
        </section>
    </body>

    </html>

<?php
} else {
    echo "Game not found.";
}
?>