<?php
include('../server/connection.php');
session_start();

// Check if session variables are set
<<<<<<< HEAD
if (!isset($_SESSION['id_user'])) {
=======
if (isset($_SESSION['id_user']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
    $id_user = $_SESSION['id_user'];
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $passkey = $_SESSION['passkey'];
    $rekening = $_SESSION['rekening'];
    $saldo = $_SESSION['saldo'];

    // Now you can use these session variables as needed
} else {
    // Redirect to login page if session variables are not set
>>>>>>> 292d1c5b8ecb500bd9c709dedf1c136ddb9b9807
    header("Location: sign-up.php");
    exit();
}

<<<<<<< HEAD
$id_user = $_SESSION['id_user'];

// Check if game_id is provided in the URL
=======
>>>>>>> 292d1c5b8ecb500bd9c709dedf1c136ddb9b9807
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

<<<<<<< HEAD
=======
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
>>>>>>> 292d1c5b8ecb500bd9c709dedf1c136ddb9b9807

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
<<<<<<< HEAD
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
                <a href="sign-up.php">Logout</a>
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
    </script>
    <!-- End of javascript dropdown -->



    <!-- Main content -->
    <div class="cart-wrapper">
        <?php while ($row = $cart->fetch_assoc()) { ?>
            <div class="cart">
                <h2>YOUR ORDER</h2>
                <div id="cart-items-container">
                    <!-- PHP loop to generate cart items -->
                    <div class="order-item">
                        <img src="../images/game-images/header/<?php echo $row['header']; ?>" alt="Game Image">
                        <div class="item-details">
                            <p class="item-name"><?php echo $row['game_name']; ?></p>
                            <p class="item-price">Rp.<?php echo number_format($row['game_price']); ?></p>
                            <!-- Add remove button with a data attribute to hold the game ID -->
                            <button class="remove-item" data-gameid="<?php echo $row['game_id']; ?>">Remove</button>
                        </div>
                    </div>
                </div>
                <p>ORDER TOTAL: <span id="order-total">Rp.</span></p>
            </div>
        <?php } ?>
        <div class="payment-option">
            <h3>YOUR PAYMENT & GIFTING DETAILS</h3>
            <br>
            <div class="payment-method">
                <label>
                    <input type="radio" name="payment" value="wallet">
                    <span class="payment-description">
                        <p>USE WALLET FUNDS (BALANCE Rp. 100.000)</p>
                    </span>
                </label>
            </div>
            <div class="order-total">
                <h5>TOTAL: </h5>
                <p id="payment-total">Rp.<?php echo number_format($total); ?></p>
                <button>PAY FOR YOUR ORDER NOW</button>
            </div>
        </div>
    </div>
    <!-- End of main content -->
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const removeButtons = document.querySelectorAll('.remove-item');

    removeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const gameId = button.getAttribute('data-gameid');
            // Send AJAX request to delete item
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_item.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Item successfully deleted
                    console.log('Item deleted successfully');
                    // Reload the page or update the cart as needed
                    window.location.reload();
                } else {
                    console.error('Error deleting item');
                }
            };
            xhr.send('game_id=' + gameId);
        });
    });
});
</script>

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

=======
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
>>>>>>> 292d1c5b8ecb500bd9c709dedf1c136ddb9b9807
</body>

</html>