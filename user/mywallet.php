<?php
include('../server/connection.php');
session_start();

$id_user = $_SESSION['id_user'];
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$passkey = $_SESSION['passkey'];
$rekening = $_SESSION['rekening'];
$saldo = $_SESSION['saldo'];

if (isset($_POST['top-up_btn'])) {
    $saldo_input = $_POST['amount'];

    $top_up_amount = $saldo_input + $saldo;

    $query_top_up = "UPDATE user SET saldo = ? WHERE id_user = ?";
    $stmt = $conn->prepare($query_top_up);
    $stmt->bind_param('ii', $top_up_amount, $id_user);

    if ($stmt->execute()) {
        // Perbarui saldo dan sesi setelah berhasil di-update di database
        $_SESSION['saldo'] = $top_up_amount;
        $saldo = $_SESSION['saldo'];
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("location: mywallet.php");
    exit;
}

if (isset($_POST['withdraw_btn'])) {
    $saldo_input = $_POST['amount'];

    if ($saldo - $saldo_input - 2500 >= 0) {
        $withdraw_amount = $saldo - $saldo_input - 2500;
        $query_withdraw = "UPDATE user SET saldo = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query_withdraw);
        $stmt->bind_param('ii', $withdraw_amount, $id_user);

        if ($stmt->execute()) {
            // Perbarui saldo dan sesi setelah berhasil di-update
            $_SESSION['saldo'] = $withdraw_amount;
            $saldo = $_SESSION['saldo'];
        }

        $stmt->close();
        $conn->close();
        header("location: mywallet.php");
        exit;
    } else {
        echo 'Saldo tersedia tidak mencukupi';
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wallet - Gamestore</title>
    <link rel="stylesheet" href="../style/mywallet.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="">Library</a></li>
            <li><a href="" class="onpage">Wallet</a></li>
            <li><a href="shopping-cart.php">Cart</a></li>
        </ul>
        <i class='bx bxs-user-circle' id="user"></i>
        <div class="sub-menu-wrap" id="sub-menu-wrap">
            <a href="profile-user.php">Manage Account</a>
            <a href="sign-up.php" id="logout">Logout</a>
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
        <h2 class="modal-title">Are you sure you want to log out?</h2>
        <div>
            <button id="confirmLogout">Yes</button>
            <button id="cancelLogout">Cancel</button>
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

    <!-- Header content -->
    <header>
        <div class="header-content">
            <h2>My Nexus Wallet</h2>
            <section class="line"></section>
            <p>CURRENT BALANCE</p>
            <h2 class="saldo">Rp. <?php echo number_format($saldo, 2, ',', '.'); ?></h2>
            <button id="top-up">TOP UP</button>
            <button id="withdraw">WITHDRAW</button>
        </div>
    </header>

    <div class="modal" id="top-up-modal">
        <div class="modal-content2">
            <span class="close-btn" id="close-top-up">&times;</span>
            <form action="mywallet.php" method="POST" class="top-up-form">
                <h2>Top Up</h2>
                <div class="line" id="wd-line"></div>
                <p>From: <?php echo $rekening?></p>
                <p>Amount:</p>
                <input type="number" name="amount" min="0" oninput="checkValue(this)" required placeholder="insert amount here">
                <button type="submit" name="top-up_btn">TOP UP</button>
            </form>
        </div>
    </div>

    <div class="modal" id="withdraw-modal">
        <div class="modal-content2">
            <span class="close-btn" id="close-withdraw">&times;</span>
            <form action="mywallet.php" method="POST" class="withdraw-form">
                <h2>Withdraw</h2>
                <div class="line" id="wd-line"></div>
                <p>Withdraw To: <?php echo $rekening ?></p>
                <p>Amount:</p>
                <input type="number" name="amount" min="0" oninput="checkValue(this)" required placeholder="insert amount here">
                <p>Admin fee: Rp. 2.500,00</p>
                <button type="submit" name="withdraw_btn">WITHDRAW</button>
            </form>
        </div>
    </div>

    <!-- input top up and withdraw >= 0 -->
    <script>
        function checkValue(input) {
            if (input.value < 0) {
                input.value = 0;
            }
        }
    </script>

    <!-- Top Up Javascript modal -->
    <script>
        // Get elements
        const topUpBtn = document.getElementById('top-up');
        const withdrawBtn = document.getElementById('withdraw');
        const topUpModal = document.getElementById('top-up-modal');
        const withdrawModal = document.getElementById('withdraw-modal');
        const closeTopUpBtn = document.getElementById('close-top-up');
        const closeWithdrawBtn = document.getElementById('close-withdraw');

        topUpBtn.onclick = function() {
            topUpModal.style.display = 'block';
        }

        withdrawBtn.onclick = function() {
            withdrawModal.style.display = 'block';
        }

        closeTopUpBtn.onclick = function() {
            topUpModal.style.display = 'none';
        }

        closeWithdrawBtn.onclick = function() {
            withdrawModal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == topUpModal) {
                topUpModal.style.display = 'none';
            }
            if (event.target == withdrawModal) {
                withdrawModal.style.display = 'none';
            }
        }
    </script>
</body>

</html>