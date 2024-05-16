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
    $query_user = "SELECT email, username, alamat FROM user WHERE id_user = ?";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/profile.css">
</head>

<body>
    <div class="container">
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo $modal_title; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo $modal_message; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal -->

        <a href="homepage.php" class="float-left"><i class="bi bi-arrow-left-circle-fill"></i></a>
        <h2 class="text-center">User Profile</h2><br>
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
                        <label for="address">Alamat:</label>
                        <div class="input-group">
                            <textarea class="form-control" id="address" name="alamat" rows="3" disabled><?php echo htmlspecialchars($user['alamat']); ?></textarea>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

        // Show the modal if there's a message to display
        <?php if (!empty($modal_title) && !empty($modal_message)) : ?>
            $(document).ready(function() {
                $('#exampleModal').modal('show');
            });
        <?php endif; ?>
    </script>
</body>

</html>