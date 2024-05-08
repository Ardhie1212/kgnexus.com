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
    <style>
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="homepage.php" class="float-left"><i class="bi bi-arrow-left-circle-fill"></i></a>
        <h2 class="text-center">User Profile</h2><br>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="../Action/action-update-user.php" method="post" onclick="return confirm('Apakah Anda yakin ingin memperbarui profil ?')">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
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
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
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
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo $user['address']; ?></textarea>
                            <div class="input-group-append">
                                <span class="input-group-text edit-icon" onclick="editInput('address')">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
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
    </script>

</body>

</html>
