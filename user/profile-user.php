<?php
include('../server/connection.php');
session_start();

// Mengambil user_id dari sesi atau dari permintaan GET/POST (tergantung bagaimana user_id diambil)
$user_id = $_SESSION['id_user'] ?? $_GET['id_user'] ?? $_POST['id_user'] ?? 0;

// Initialize variables for modal
$modal_title = "";
$modal_message = "";

if (isset($_GET['success'])) {
    $modal_title = "Success";
    $modal_message = "Your profile has successfully been updated!";
} elseif (isset($_GET['error'])) {
    $modal_title = "Error";
    $modal_message = "Your profile failed to update!";
}

if ($user_id) {
    $query_user = "SELECT email, username, rekening FROM user WHERE id_user = ?";
    $stmt = $conn->prepare($query_user);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result_user = $stmt->get_result();

    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>

<body>
    <!-- Navbar -->
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
   <div id="logoutModal" class="modal-content">
        <span class="closeX">&times;</span>
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
        var modalLogout = document.getElementById('logoutModal');
        var closeModal = modalLogout.querySelector('.closeX');

        logoutBtn.addEventListener('click', function(event) {
            event.preventDefault();
            modalLogout.style.display = "block";
            centerModal();
        });

        closeModal.addEventListener('click', function() {
            modalLogout.style.display = "none";
        });

        window.addEventListener('click', function(event) {
            if (event.target == modalLogout) {
                modalLogout.style.display = "none";
            }
        });

        document.getElementById("confirmLogout").addEventListener("click", function() {
            window.location.href = "sign-up.php";
        });

        document.getElementById("cancelLogout").addEventListener("click", function() {
            modalLogout.style.display = "none";
        });
    </script>
    <!-- End of Javascript Logout Modal -->

    <!-- Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content1">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2><?php echo $modal_title; ?></h2>
            </div>
            <div class="modal-body">
                <p><?php echo $modal_message; ?></p>
            </div>
            <div class="modal-footer">
                <button class="modal-button" id="closeBtn">Close</button>
            </div>
        </div>
    </div>

    <!-- End of Modal -->
    <script>
        // Get the modal
        var messageModal = document.getElementById('messageModal');

        // Get the <span> element that closes the modal
        var span = messageModal.getElementsByClassName('close')[0];

        // Get the button that closes the modal
        var closeBtn = document.getElementById('closeBtn');

        // When the user clicks on <span> (x) or the close button, close the modal
        span.onclick = function() {
            messageModal.style.display = 'none';
        }
        closeBtn.onclick = function() {
            messageModal.style.display = 'none';
        }

        // Show the modal if there's a message to display
        <?php if (!empty($modal_title) && !empty($modal_message)) : ?>
            window.onload = function() {
                messageModal.style.display = 'block';
            }
        <?php endif; ?>

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == messageModal) {
                messageModal.style.display = 'none';
            }
        }
    </script>

    <div class="container">
        <i class='bx bx-user-circle'></i>
        <h2 class="text-center">Your Profile</h2><br>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="profileForm" action="../Action/action-update-user.php" method="post">
                    <input type="hidden" name="id_user" value="<?php echo $user_id; ?>">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required disabled>
                            <div class="input-group-append">
                                <span class="input-group-text edit-icon" onclick="editInput('email')">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required disabled>
                            <div class="input-group-append">
                                <span class="input-group-text edit-icon" onclick="editInput('username')">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Bank Account:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="address" name="rekening" rows="3" value="<?php echo htmlspecialchars($user['rekening']); ?>" required disabled></input>
                            <div class="input-group-append">
                                <span class="input-group-text edit-icon" onclick="editInput('address')">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="confirmSave()">Save</button>
                </form>
            </div>
        </div>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <script>
        function editInput(inputId) {
            var input = document.getElementById(inputId);
            input.disabled = false;
            input.focus();
        }

        function confirmSave() {
            if (confirm('Confirmation: Are you sure you want to keep the changes?')) {
                document.getElementById('profileForm').submit();
            }
        }
    </script>
</body>

</html>