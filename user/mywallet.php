<?php
include('../server/connection.php');
session_start();

$id_user = $_SESSION['id_user'];
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$passkey = $_SESSION['passkey'];
$rekening = $_SESSION['rekening'];
$saldo = $_SESSION['saldo'];

$top_up_success = false;
$withdraw_success = false;
$withdraw_failure = false;

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
        $top_up_success = true;
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    if ($top_up_success) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('success-modal').style.display = 'block';
        });</script>";
    } else {
        header("location: mywallet.php");
        exit;
    }
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
            $withdraw_success = true;
        }

        $stmt->close();
        $conn->close();
        if ($withdraw_success) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('withdraw-success-modal').style.display = 'block';
            });</script>";
        } else {
            header("location: mywallet.php");
            exit;
        }
    } else {
        $withdraw_failure = true;
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('withdraw-failure-modal').style.display = 'block';
        });</script>";
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
            <li><a href="library.php">Library</a></li>
            <li><a href="mywallet.php">Wallet</a></li>
            <li><a href="shopping-cart.php">Cart</a></li>
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
            var modal = document.querySelector('.modal-content');
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
                <p>From: <?php echo $rekening ?></p>
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

    <!-- Success Modal -->
    <div class="modal" id="success-modal">
        <div class="modal-content2">
            <span class="close-btn" id="close-success">&times;</span>
            <h2>Success</h2>
            <div class="line" id="success-line"></div>
            <p>Your top-up was successful!</p>
            <button type="button" id="ok-button" class="modal-btn">OK</button>
        </div>
    </div>

    <!-- Withdrawal Success Modal -->
    <div class="modal" id="withdraw-success-modal">
        <div class="modal-content2">
            <span class="close-btn" id="close-withdraw-success">&times;</span>
            <h2>Success</h2>
            <div class="line" id="withdraw-success-line"></div>
            <p>Your withdrawal was successful!</p>
            <button type="button" id="withdraw-ok-button" class="modal-btn">OK</button>
        </div>
    </div>

    <!-- Withdrawal Failure Modal -->
    <div class="modal" id="withdraw-failure-modal">
        <div class="modal-content2">
            <span class="close-btn" id="close-withdraw-failure">&times;</span>
            <h2>Failure</h2>
            <div class="line" id="withdraw-failure-line"></div>
            <p>Your withdrawal failed. Insufficient funds.</p>
            <button type="button" id="withdraw-failure-ok-button" class="modal-btn">OK</button>
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

    <!-- Success Top Up Modal Javascript -->
    <script>
        const successModal = document.getElementById('success-modal');
        const closeSuccessBtn = document.getElementById('close-success');
        const okBtn = document.getElementById('ok-button');

        closeSuccessBtn.onclick = function() {
            successModal.style.display = 'none';
            window.location.href = 'mywallet.php';
        }

        okBtn.onclick = function() {
            successModal.style.display = 'none';
            window.location.href = 'mywallet.php';
        }

        window.onclick = function(event) {
            if (event.target == successModal) {
                successModal.style.display = 'none';
                window.location.href = 'mywallet.php'; 
            }
        }
    </script>

    <!-- Success Withdraw Modal JavaScript -->
    <script>
        const withdrawSuccessModal = document.getElementById('withdraw-success-modal');
        const closeWithdrawSuccessBtn = document.getElementById('close-withdraw-success');
        const withdrawOkBtn = document.getElementById('withdraw-ok-button');

        closeWithdrawSuccessBtn.onclick = function() {
            withdrawSuccessModal.style.display = 'none';
            window.location.href = 'mywallet.php'; 
        }

        withdrawOkBtn.onclick = function() {
            withdrawSuccessModal.style.display = 'none';
            window.location.href = 'mywallet.php'; 
        }

        window.onclick = function(event) {
            if (event.target == withdrawSuccessModal) {
                withdrawSuccessModal.style.display = 'none';
                window.location.href = 'mywallet.php';
            }
        }
    </script>

    <!-- Failure Withdraw Modal JavaScript -->
    <script>
        const withdrawFailureModal = document.getElementById('withdraw-failure-modal');
        const closeWithdrawFailureBtn = document.getElementById('close-withdraw-failure');
        const withdrawFailureOkBtn = document.getElementById('withdraw-failure-ok-button');

        closeWithdrawFailureBtn.onclick = function() {
            withdrawFailureModal.style.display = 'none';
            window.location.href = 'mywallet.php'; 
        }

        withdrawFailureOkBtn.onclick = function() {
            withdrawFailureModal.style.display = 'none';
            window.location.href = 'mywallet.php'; 
        }

        window.onclick = function(event) {
            if (event.target == withdrawFailureModal) {
                withdrawFailureModal.style.display = 'none';
                window.location.href = 'mywallet.php'; 
            }
        }
    </script>
</body>

</html>