<?php
include('../server/connection.php');
session_start();

// Check if session variables are set
if (isset($_SESSION['id_user']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
    $id_user = $_SESSION['id_user'];
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $passkey = $_SESSION['passkey'];
    $alamat = $_SESSION['alamat'];

    // Now you can use these session variables as needed
} else {
    // Redirect to login page if session variables are not set
    header("Location: sign-up.php");
    exit();
}



if (isset($_GET['game_id'])) {
    $gameId = $_GET['game_id'];
    $check_query = "SELECT * FROM cart WHERE game_id = $gameId AND id_user = $id_user";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        $query_cart_insert = "INSERT INTO cart (game_id, id_user) VALUES ($gameId, $id_user)";
        mysqli_query($conn, $query_cart_insert);
    } else {
        echo "Game sudah ada di keranjang.";
    }
}

$query_cart = 
"SELECT game.*,
IF(game.Sector = 'SALE', game.game_price * 0.7, game.game_price) AS price
FROM game 
JOIN cart ON game.game_id = cart.game_id
WHERE cart.id_user = $id_user";

$query_total = 
"SELECT SUM(game.game_price) AS total_price FROM game JOIN cart ON game.game_id = cart.game_id WHERE cart.id_user = $id_user";
$stmt_total = mysqli_query($conn,$query_total);
$total = mysqli_fetch_assoc($stmt_total);

$query_discount =
"SELECT SUM(game.game_price * 0.3) AS total_discounted_price FROM game 
JOIN cart ON game.game_id = cart.game_id WHERE cart.id_user = $id_user AND game.Sector = 'SALE'";
$stmt_discount = mysqli_query($conn,$query_discount);
$discount = mysqli_fetch_assoc($stmt_discount);

$subtotal = $total['total_price'] - $discount['total_discounted_price'];

$stmt_cart = $conn->prepare($query_cart);
$stmt_cart->execute();
$game = $stmt_cart->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../style/shopping-cart.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="">Library</a></li>
            <li><a href="">Wallet</a></li>
            <li><a href="shopping-cart.php" class="onpage">Cart</a></li>
        </ul>
        <i class='bx bxs-user-circle' id="user"></i>
        <div class="sub-menu-wrap" id="sub-menu-wrap">
            <a href="profile-user.php">Manage Account</a>
            <a href="sign-up.php" onclick='confirmLogout()'>Logout</a>
        </div>
    </nav>

    <div class="cart-container">
        <h1>My Cart</h1>
        <section class="line"></section>
        <div class="cart-content">
            <table class="cart-table">
                <thead>
                    <tr>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $game->fetch_assoc()) {?>
                        <tr>
                            <td><img src="../images/game-images/header/<?php echo $row['header'] ?>" alt=""></td>
                            <td><?php echo $row['game_name'] ?></td>
                            <td>
                                <?php if (isset($row['price']) && $row['price'] < $row['game_price']) : ?>
                                    <p class="price"><s>Rp. <?php echo number_format($row['game_price'], 2, ',', '.'); ?></s></p>
                                    <p>Rp. <?php echo number_format($row['price'], 2, ',', '.'); ?></p>
                                <?php else : ?>
                                    <p class="price">Rp. <?php echo number_format($row['game_price'], 2, ',', '.'); ?></p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <p class="remove">Remove</p>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="cart-summary">
                <h2>Total:</h2>
                <p id="total">Rp. <?php echo number_format($total['total_price'], 2, ',', '.'); ?></p>
                <h2>Diskon:</h2>
                <p>Rp. <?php echo number_format($discount['total_discounted_price'], 2, ',', '.'); ?></p>
                <h2>Subtotal:</h2>
                <p>Rp. <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
                <h2>Saldo</h2>
                <p>Rp 1,000,000</p>
                <button class="checkout-btn"><strong>CHECK OUT</strong></button>
            </div>
        </div>
    </div>

    <section class="footer">
        <p>Designed by Kelompok 1</p>
        <p>Copyright © All rights reserved.</p>
    </section>
</body>

</html>