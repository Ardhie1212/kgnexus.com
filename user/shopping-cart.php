<?php
include('../server/connection.php');
session_start();

// Check if session variables are set
if (!isset($_SESSION['id_user'])) {
    header("Location: sign-up.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Check if game_id is provided in the URL
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

</body>

</html>