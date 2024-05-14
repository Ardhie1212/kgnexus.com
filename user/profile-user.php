<?php
include('../server/connection.php');

// Mengambil user_id dari sesi atau dari permintaan GET/POST (tergantung bagaimana user_id diambil)
$user_id = $_SESSION['user_id'] ?? $_GET['user_id'] ?? $_POST['user_id'] ?? 0;

if ($user_id) {
    $query_user = "SELECT user_email, user_name, user_address FROM user WHERE user_id = ?";
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
        <a href="homepage.php" class="float-left"><i class="bi bi-arrow-left-circle-fill"></i></a>
        <h2 class="text-center">User Profile</h2><br>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="profileForm" action="../Action/action-update-user.php" method="post">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required disabled>
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
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['user_name']); ?>" required disabled>
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
                            <textarea class="form-control" id="address" name="address" rows="3" disabled><?php echo htmlspecialchars($user['user_address']); ?></textarea>
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

    <script>
        function editInput(inputId) {
            var input = document.getElementById(inputId);
            input.disabled = false;
            input.focus();
        }

        function confirmSave() {
            if (confirm('Apakah Anda yakin ingin menyimpan perubahan?')) {
                document.getElementById('profileForm').submit();
            }
        }
    </script>

</body>

</html>
