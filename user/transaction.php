<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <link rel="stylesheet" href="../style/transaction.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
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

    <!-- Cart Container -->
    <div class="cart-container">
        <h1>Review + Purchase</h1>
        <section class="line"></section>
        <div class="cart-content">
            <table class="cart-table">
                <tbody>
                    <tr>
                        <td>
                            <img src="../images/game-images/header/sample.jpg" alt="Product Image">
                            <span>Rise Of The White Sun</span>
                        </td>
                        <td>Rp 74,999</td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-summary">
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span>Rp 74,999</span>
                </div>
                <div class="summary-item">
                    <span>Total:</span>
                    <span>Rp 74,999</span>
                </div>
                <div class="payment-method">
                    <span>Payment method:</span>
                    <span>KGNexus Wallet (RP.)</span>
                    <a href="mywallet.php"><button>TopUP!</button></a>
                </div>
                <div class="account-info">
                    <span>KGNexus account:</span>
                    <span>Username</span>
                </div>
                <a href="transaction.php"><button class="checkout-btn"><strong>Purchase</strong></button></a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <section class="footer">
        <p>Designed by Kelompok 1</p>
        <p>Copyright © All rights reserved.</p>
    </section>

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
</body>
</html>
