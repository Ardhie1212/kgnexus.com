<?php
include('../server/connection.php');

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // Create
        $game_name = $_POST['game_name'];
        $stmt = $conn->prepare("INSERT INTO game (game_name) VALUES (?)");
        $stmt->bind_param("s", $game_name);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Update
        $id = $_POST['id'];
        $game_name = $_POST['game_name'];
        $game_desc = $_POST['game_desc'];
        $game_category = $_POST['game_category'];
        $game_company = $_POST['game_company'];
        $size = $_POST['size'];
        $release_date = $_POST['release_date'];
        $rating = $_POST['rating'];
        $header = $_POST['header'];
        $sector = $_POST['sector'];
        $game_price = $_POST['game_price'];

        // File upload handling
        $uploadDir = "../images/game-images/";
        $photo1 = handleFileUpload('photo1', $uploadDir);
        $photo2 = handleFileUpload('photo2', $uploadDir);
        $photo3 = handleFileUpload('photo3', $uploadDir);
        $video = handleFileUpload('video', $uploadDir);

        // Update database
        $stmt = $conn->prepare("UPDATE game SET game_name = ?, game_desc = ?, game_category = ?, game_company = ?, size = ?, release_date = ?, rating = ?, header = ?, photo1 = ?, photo2 = ?, photo3 = ?, video = ?, sector = ?, game_price = ? WHERE game_id = ?");
        $stmt->bind_param("ssssssssssssssi", $game_name, $game_desc, $game_category, $game_company, $size, $release_date, $rating, $header, $photo1, $photo2, $photo3, $video, $sector, $game_price, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        // Delete
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM game WHERE game_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Function to handle file uploads
function handleFileUpload($inputName, $uploadDir)
{
    if (!empty($_FILES[$inputName]['name'])) {
        $targetFile = $uploadDir . basename($_FILES[$inputName]['name']);
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
            return $targetFile;
        } else {
            // File upload failed
            return $_POST[$inputName]; // Return the existing file path
        }
    } else {
        // No file uploaded, return existing file path
        return $_POST[$inputName];
    }
}

// Fetch data for the chart, total users, and total income
$query_visual = "SELECT g.game_name, COUNT(t.transaction_id) as count
FROM transaction t
JOIN game g ON t.game_id = g.game_id
GROUP BY g.game_name";
$result = $conn->query($query_visual);
$dataPoints = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $dataPoints[] = ["label" => $row['game_name'], "y" => $row['count']];
    }
}

$query_users = "SELECT COUNT(*) as total_users FROM user";
$result_users = $conn->query($query_users);
$total_users = 0;
if ($result_users) {
    $row = $result_users->fetch_assoc();
    $total_users = $row['total_users'];
}

$query_income = "
    SELECT SUM(total_sales) AS total_income
    FROM (
        SELECT g.game_price * COUNT(t.transaction_id) AS total_sales
        FROM transaction t
        JOIN game g ON t.game_id = g.game_id
        GROUP BY g.game_id
    ) AS sales";
$result_income = $conn->query($query_income);
$total_income = 0.0;
if ($result_income) {
    $row = $result_income->fetch_assoc();
    $total_income = $row['total_income'];
}

$query_games = "SELECT game_id, game_name, game_category, game_company, size, release_date, rating, header, photo1, photo2, photo3, video, sector, game_price FROM game";
$result_games = $conn->query($query_games);
$games = [];
if ($result_games) {
    while ($row = $result_games->fetch_assoc()) {
        $games[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE HTML>
<html>

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Style for sidebar */
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .hamburger {
            font-size: 30px;
            cursor: pointer;
            position: fixed;
            top: 15px;
            right: 20px;
            z-index: 2;
            color: #111;
        }
    </style>
    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }

        function openUpdateModal(game) {

            var modalTitle = document.getElementById('updateModalLabel');
            if (game.hasOwnProperty('game_id')) {
                modalTitle.textContent = "Update Game";
            } else {
                modalTitle.textContent = "Add New Game";
            }
            document.getElementById('updateForm').reset();
            document.getElementById('update_id').value = game.game_id;
            document.getElementById('update_game_name').value = game.game_name;
            document.getElementById('update_game_desc').value = game.game_desc;
            document.getElementById('update_game_category').value = game.game_category;
            document.getElementById('update_game_company').value = game.game_company;
            document.getElementById('update_size').value = game.size;
            document.getElementById('update_release_date').value = game.release_date;
            document.getElementById('update_rating').value = game.rating;
            document.getElementById('update_header').value = game.header;
            document.getElementById('update_photo1').value = game.photo1;
            document.getElementById('update_photo2').value = game.photo2;
            document.getElementById('update_photo3').value = game.photo3;
            document.getElementById('update_video').value = game.video;
            document.getElementById('update_sector').value = game.sector;
            document.getElementById('update_game_price').value = game.game_price;

            // Tentukan judul modal berdasarkan tindakan
            // Tentukan judul modal berdasarkan tindakan



            $('#updateModal').modal('show');
        }


        window.onload = function() {
            var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
            console.log(dataPoints); // Debugging: Print data points in the browser console

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Penjualan Tertinggi",
                    fontSize: 30
                },
                axisY: {
                    title: "Jumlah Pembelian"
                },
                data: [{
                    type: "column",
                    yValueFormatString: "#,##0.##",
                    dataPoints: dataPoints
                }]
            });
            chart.render();
        }
    </script>
</head>

<body>
    <span>
        <h1 style="background-color: #111; color: white; padding: 25px;">Dashboard Admin</h1>
    </span>
    <div class="hamburger" style="color: white;" onclick="openNav()">&#9776;</div>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="list-transaction.php">List Transaksi</a>
        <a href="dashboard-admin.php">List Games</a>
        <a href="#" onclick="confirmLogout()">Logout</a>
    </div>
    <!-- Modal for Logout -->
    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" role="dialog" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function confirmLogout() {
            $('#confirmLogoutModal').modal('show');
        }

        function logout() {
            window.location.href = 'login-admin.php'; // Redirect to logout page
        }
    </script>

    <div class="row align-items-center">
        <div class="col-md-8">
            <div id="chartContainer" style="height: 300px; width : 80%; "></div>
        </div>
        <div class="col-md-4">
            <div style="margin-left: 20px;">
                <h3>
                    <span class="bi bi-people"></span>
                    Total Registered Users: <br><?php echo $total_users; ?>
                </h3>
                <h3>
                    <span class="bi bi-currency-dollar"></span>
                    Total Income:<br> Rp <?php echo number_format($total_income, 2, ',', '.'); ?>
                </h3>
            </div>
        </div>
    </div>


    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

    <!-- CRUD Form for Adding a New Game -->


    <!-- Games List -->

    <div class="d-flex justify-content-between mb-3">
        <h2>Games List</h2>
        <form method="POST" enctype="multipart/form-data">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">Add New Game</button>
        </form>
    </div>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Game Name</th>
                <th>Category</th>
                <th>Size</th>
                <th>Release</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($games as $game) : ?>
                <tr>
                    <td><?php echo $game['game_id']; ?></td>
                    <td><?php echo $game['game_name']; ?></td>
                    <td><?php echo $game['game_category']; ?></td>
                    <td><?php echo $game['size']; ?></td>
                    <td><?php echo $game['release_date']; ?></td>
                    <td><?php echo $game['game_price']; ?></td>
                    <td>
                        <!-- Update Button -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal" onclick='openUpdateModal(<?php echo json_encode($game); ?>)'>Update</button>

                        <!-- Delete Form -->
                        <form id="deleteForm_<?php echo $game['game_id']; ?>" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $game['game_id']; ?>">
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $game['game_id']; ?>)">Delete</button>
                        </form>
                        <!-- MODAL FOR DELETE -->
                        <div class="modal fade" id="confirmDeleteModal_<?php echo $game['game_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus game ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-danger" onclick="deleteGame(<?php echo $game['game_id']; ?>)">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- SCRIPT DELETE -->
                        <script>
                            function confirmDelete(gameId) {
                                $('#confirmDeleteModal_' + gameId).modal('show');
                            }

                            function deleteGame(gameId) {
                                $('#confirmDeleteModal_' + gameId).modal('hide');
                                document.getElementById('deleteForm_' + gameId).submit();
                            }
                        </script>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" id="updateForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Update Game</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="update_id">
                        <div class="form-group">
                            <label for="update_game_name">Game Name</label>
                            <input type="text" class="form-control" id="update_game_name" name="game_name" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_desc">Game Description</label>
                            <input type="text" class="form-control" id="update_game_desc" name="game_desc" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_category">Game Category</label>
                            <input type="text" class="form-control" id="update_game_category" name="game_category" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_company">Game Company</label>
                            <input type="text" class="form-control" id="update_game_company" name="game_company" required>
                        </div>
                        <div class="form-group">
                            <label for="update_size">Size</label>
                            <input type="text" class="form-control" id="update_size" name="size" required>
                        </div>
                        <div class="form-group">
                            <label for="update_release_date">Release Date</label>
                            <input type="date" class="form-control" id="update_release_date" name="release_date" required>
                        </div>
                        <div class="form-group">
                            <label for="update_rating">Rating</label>
                            <input type="number" class="form-control" id="update_rating" name="rating" step="0.1" required>
                        </div>
                        <div class="form-group">
                            <label for="update_header">Header</label>
                            <input type="text" class="form-control" id="update_header" name="header" required>
                        </div>
                        <div class="form-group">
                            <label for="update_photo1">Photo 1</label>
                            <input type="file" class="form-control" id="update_photo1" name="photo1">
                        </div>
                        <div class="form-group">
                            <label for="update_photo2">Photo 2</label>
                            <input type="file" class="form-control" id="update_photo2" name="photo2">
                        </div>
                        <div class="form-group">
                            <label for="update_photo3">Photo 3</label>
                            <input type="file" class="form-control" id="update_photo3" name="photo3">
                        </div>
                        <div class="form-group">
                            <label for="update_video">Video</label>
                            <input type="file" class="form-control" id="update_video" name="video">
                        </div>
                        <div class="form-group">
                            <label for="update_sector">Sector</label>
                            <input type="text" class="form-control" id="update_sector" name="sector" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_price">Game Price</label>
                            <input type="number" class="form-control" id="update_game_price" name="game_price" step="0.01" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                    </div>

            </div>

            </form>
        </div>
    </div>
    <!-- MODEL FOR ADD -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" id="addForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add New Game</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields for adding a new game -->
                        <div class="form-group">
                            <label for="add_game_name">Game Name</label>
                            <input type="text" class="form-control" id="add_game_name" name="game_name" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_desc">Game Description</label>
                            <input type="text" class="form-control" id="update_game_desc" name="game_desc" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_category">Game Category</label>
                            <input type="text" class="form-control" id="update_game_category" name="game_category" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_company">Game Company</label>
                            <input type="text" class="form-control" id="update_game_company" name="game_company" required>
                        </div>
                        <div class="form-group">
                            <label for="update_size">Size</label>
                            <input type="text" class="form-control" id="update_size" name="size" required>
                        </div>
                        <div class="form-group">
                            <label for="update_release_date">Release Date</label>
                            <input type="date" class="form-control" id="update_release_date" name="release_date" required>
                        </div>
                        <div class="form-group">
                            <label for="update_rating">Rating</label>
                            <input type="number" class="form-control" id="update_rating" name="rating" step="0.1" required>
                        </div>
                        <div class="form-group">
                            <label for="update_header">Header</label>
                            <input type="text" class="form-control" id="update_header" name="header" required>
                        </div>
                        <div class="form-group">
                            <label for="update_photo1">Photo 1</label>
                            <input type="file" class="form-control" id="update_photo1" name="photo1">
                        </div>
                        <div class="form-group">
                            <label for="update_photo2">Photo 2</label>
                            <input type="file" class="form-control" id="update_photo2" name="photo2">
                        </div>
                        <div class="form-group">
                            <label for="update_photo3">Photo 3</label>
                            <input type="file" class="form-control" id="update_photo3" name="photo3">
                        </div>
                        <div class="form-group">
                            <label for="update_video">Video</label>
                            <input type="file" class="form-control" id="update_video" name="video">
                        </div>
                        <div class="form-group">
                            <label for="update_sector">Sector</label>
                            <input type="text" class="form-control" id="update_sector" name="sector" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_price">Game Price</label>
                            <input type="number" class="form-control" id="update_game_price" name="game_price" step="0.01" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="add" class="btn btn-primary">Add Game</button>
            </div>
            </form>
        </div>
    </div>
    </div>





    <!-- Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>