<?php
include('../server/connection.php');
session_start();

// Check if session variables are set
if (!isset($_SESSION['id_user'])) {
    header("Location: sign-up.php");
    exit();
}

if (isset($_GET['game_id'])) {
    // Get the game_id from the URL
    $game_id = $_GET['game_id'];

    // Check if the game is already in the cart for this user
    $check_query = "SELECT * FROM cart WHERE game_id = ? AND id_user = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $game_id, $id_user);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "This game is already in your cart.";
    } else {
        // Prepare and bind the INSERT statement
        $insert_query = "INSERT INTO cart (game_id, id_user) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ii", $game_id, $id_user);

        // Execute the statement
        if ($insert_stmt->execute()) {
            echo "Game added to cart successfully.";
        } else {
            echo "Error adding game to cart: " . $insert_stmt->error;
        }

        // Close the statement
        $insert_stmt->close();
    }

    // Close the check statement and result
    $check_stmt->close();
    $check_result->close();
}

$query_cart =
    "SELECT game.*,
IF(game.Sector = 'SALE', game.game_price * 0.7, game.game_price) AS price
FROM game 
JOIN cart ON game.game_id = cart.game_id
WHERE cart.id_user = $id_user";

$query_total =
    "SELECT SUM(game.game_price) AS total_price FROM game JOIN cart ON game.game_id = cart.game_id WHERE cart.id_user = $id_user";
$stmt_total = mysqli_query($conn, $query_total);
$total = mysqli_fetch_assoc($stmt_total);

$query_discount =
    "SELECT SUM(game.game_price * 0.3) AS total_discounted_price FROM game 
JOIN cart ON game.game_id = cart.game_id WHERE cart.id_user = $id_user AND game.Sector = 'SALE'";
$stmt_discount = mysqli_query($conn, $query_discount);
$discount = mysqli_fetch_assoc($stmt_discount);

$subtotal = $total['total_price'] - $discount['total_discounted_price'];

$query_cart = "SELECT * FROM game JOIN cart ON game.game_id = cart.game_id WHERE cart.id_user = $id_user";
$stmt_cart = $conn->prepare($query_cart);
$stmt_cart->execute();
$cart = $stmt_cart->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['game_id'])) {
    $gameId = $_POST['game_id'];

    // Prepare and execute the DELETE query
    $query = "DELETE FROM cart WHERE game_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $gameId); // Assuming game_id is an integer
    if ($stmt->execute()) {
        // Deletion successful
        http_response_code(200); // Set response status code to 200 (OK)
        echo "Item deleted successfully";
    } else {
        // Deletion failed
        http_response_code(500); // Set response status code to 500 (Internal Server Error)
        echo "Error deleting item";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    http_response_code(400); // Set response status code to 400 (Bad Request)
    echo "Invalid request";
}
// Close the database connection
$conn->close();
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
            <li><a href="homepage.php" >Home</a></li>
            <li><a href="library.php">Library</a></li>
            <li><a href="mywallet.php">Wallet</a></li>
            <li><a href="shopping-cart.php" class="onpage">Cart</a></li>
        </ul>
        <i class='bx bxs-user-circle' id="user"></i>
        <div class="sub-menu-wrap" id="sub-menu-wrap">
            <a href="profile-user.php">Manage Account</a>
            <a href="sign-up.php" id="logout">Sign out</a>
        </div>
    </nav>
    <!-- Javascript dropdown -->
    <script>
        document.getElementById('user').addEventListener('click', function() {
            document.getElementById('sub-menu-wrap').classList.toggle('sub-menu-show');
        });

        function confirmLogout() {
            modal.style.display = "block";
            centerModal();
        }
    </script>

    <!-- Logout Modal -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="icon">
            <i class='bx bx-message-alt-error'></i>
        </div>
        <h2>Confirm</h2>
        <p class="modal-title">Are you sure you want to Sign out?</p>
        <div>
            <button id="confirmLogout">Yes</button>
            <button id="cancelLogout">No</button>
        </div>
    </div>
    <!-- End of Logout Modal -->

    <!-- Javascript Logout Modal -->
    <script>
        function centerModal() {
            var modal = document.querySelector('.modal-content');
            modal.style.top = "50%";
            modal.style.left = "50%";
            modal.style.transform = "translate(-50%, -50%)";
        }

        window.addEventListener('resize', centerModal);

        var logoutBtn = document.getElementById("logout");
        var modal = document.querySelector('.modal-content');
        var closeModal = document.querySelector('.close');

        logoutBtn.addEventListener('click', function() {
            modal.style.display = "block";
            centerModal();
        });

        closeModal.addEventListener('click', function() {
            modal.style.display = "none";
        });

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

        document.getElementById("confirmLogout").addEventListener("click", function() {
            window.location.href = "sign-up.php";
        });

        document.getElementById("cancelLogout").addEventListener("click", function() {
            modal.style.display = "none";
        });

        document.getElementById("logout").addEventListener('click', function(event) {
            event.preventDefault();
            confirmLogout();
        });
    </script>
    <!-- End of Javascript Logout Modal -->

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
                    <?php while ($row = $game->fetch_assoc()) { ?>
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
                                <a href="../server/deletecart.php?game_id=<?= $row['game_id'] ?>" class="remove">Remove</a>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
            <div class="cart-summary">
                <div class="summary-item">
                    <span>Summary</span>
                </div>
                <div class="summary-item">
                    <span>Price:</span>
                    <span>Rp. <?php echo number_format($total['total_price'], 2, ',', '.'); ?></span>
                </div>
                <div class="summary-item">
                    <span>Discount:</span>
                    <span>- Rp. <?php echo number_format($discount['total_discounted_price'], 2, ',', '.'); ?></span>
                </div>
                <div class="line" id="line"></div>
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span>Rp. <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                </div>
                <button class="checkout-btn"><strong>CHECK OUT</strong></button>
                <div class="summary-item">
                    <span>Saldo:</span>
                    <span>Rp. <?php echo number_format($saldo, 2, ',', '.'); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>