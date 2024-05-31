<?php
include('../server/connection.php');

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['delete'])) {
        // Delete
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM game WHERE game_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
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
SELECT
  SUM(g.game_price * COUNT(DISTINCT t.user_id)) AS total_income
FROM transaction t
INNER JOIN game g ON t.game_id = g.game_id
WHERE t.status NOT IN ('refund', 'Verified Refund', 'claimed')";


$result_income = $conn->query($query_income);
$total_income = 0.0; // Inisialisasi default total income ke 0.0

if ($result_income && $result_income->num_rows > 0) {
    $row = $result_income->fetch_assoc();
    $total_income = $row['total_income'];
}


$query_games = "SELECT * FROM game";
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../style/dashboard-admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

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

        #main {
            transition: margin-left .5s;
        }
    </style>

    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }


        $(document).ready(function() {
            $('#gamesTable').DataTable({
                "paging": true, // Aktifkan pagination
                "pagingType": "full_numbers", // Tipe pagination yang digunakan
                "dom": 'lrtip' // Tampilkan pagination, info, dan pencarian
            });
        });


        function openUpdateModal(game) {
            document.getElementById('update_id').value = game.game_id;
            document.getElementById('update_game_name').value = game.game_name;
            document.getElementById('update_game_desc').value = game.game_desc;
            document.getElementById('update_game_category').value = game.game_category;
            document.getElementById('update_game_company').value = game.game_company;
            document.getElementById('update_size').value = game.size;
            document.getElementById('update_release_date').value = game.release_date;
            document.getElementById('update_rating').value = game.rating;
            document.getElementById('existing_header').value = game.header;
            document.getElementById('existing_photo1').value = game.photo1;
            document.getElementById('existing_photo2').value = game.photo2;
            document.getElementById('existing_photo3').value = game.photo3;
            document.getElementById('existing_video').value = game.video;
            document.getElementById('update_sector').value = game.sector;
            document.getElementById('update_game_price').value = game.game_price;
            $('#updateModal').modal('show');
        }

        window.onload = function() {
            var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
            console.log(dataPoints); // Debugging: Print data points in the browser console

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                backgroundColor: "transparent",
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
                    dataPoints: dataPoints,
                    color: "#FEB941"
                    // Set warna batang menjadi FEB941
                }],
                axisX: { // Menghilangkan grid pada sumbu X
                    gridThickness: 0,
                    tickLength: 0,
                    lineThickness: 0,
                    labelFontSize: 0,
                    titleFontSize: 0
                },
                axisY: { // Menghilangkan grid pada sumbu Y
                    gridThickness: 0
                }
            });
            chart.render();
        }
    </script>
</head>

<body>

    

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="list-transaction.php">List Transaksi</a>
        <a href="dashboard-admin.php">List Games</a>
        <a href="#" onclick="confirmLogout()">Logout</a>
    </div>
    <div id="main">
        <button class="openbtn" onclick="openNav()"><i class="bi bi-list"></i></button>
        <div class="container">
            <h1 style="text-align: center; font-weight: bold; font-size: 60px; margin-bottom: 40px;">Dashboard Admin</h1>
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
                        <h3 class="text-center" style="display: inline-block;margin-right: 50px;">
                            <i class="bi bi-people large-icon"></i>
                            <br>
                            <span style="font-size: 1rem;">Total Registered Users:</span>
                            <br>
                            <span style="font-size: 1.5rem;"><?php echo $total_users; ?></span>
                        </h3>
                        <h3 class="text-center" style="display: inline-block;" ;>
                            <i class="bi bi-currency-dollar large-icon"></i>
                            <br>
                            <span style="font-size: 1rem;">Total Income:</span>
                            <br>
                            <span style="font-size: 1.5rem;">Rp <?php echo number_format($total_income, 2, ',', '.'); ?></span>
                        </h3>
                    </div>
                </div>
            </div>


            <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
            <!-- add games -->
            <div class="d-flex justify-content-between mb-3">
                <h2 style="font-weight: bold;">Games List</h2>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Game</button>
                <!-- Add Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">Add Game</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="../Action/action-add-games.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="game_name">Game Name</label>
                                        <input type="text" class="form-control" id="game_name" name="game_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="game_desc">Game Description</label>
                                        <textarea class="form-control" id="game_desc" name="game_desc" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="game_category">Game Category</label>
                                        <select class="form-control" id="game_category" name="game_category" required>
                                            <option value="Action">Action</option>
                                            <option value="Adventure">Adventure</option>
                                            <option value="Simulation">Simulation</option>
                                            <option value="Sports And Racing">Sports And Racing</option>
                                            <option value="Strategy">Strategy</option>
                                            <option value="Role-Playing">Role-Playing</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="game_company">Game Company</label>
                                        <input type="text" class="form-control" id="game_company" name="game_company" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <input type="text" class="form-control" id="size" name="size" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="release_date">Release Date</label>
                                        <input type="date" class="form-control" id="release_date" name="release_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="rating">Rating</label>
                                        <input type="text" class="form-control" id="rating" name="rating" values="<?php echo $row['rating'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="header">Header</label>
                                        <input type="file" class="form-control" id="header" name="header" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="photo1">Photo 1</label>
                                        <input type="file" class="form-control" id="photo1" name="photo1" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="photo2">Photo 2</label>
                                        <input type="file" class="form-control" id="photo2" name="photo2" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="photo3">Photo 3</label>
                                        <input type="file" class="form-control" id="photo3" name="photo3" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="video">Video</label>
                                        <input type="file" class="form-control" id="video" name="video" accept="video/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="sector">Sector</label>
                                        <select class="form-control" id="sector" name="sector" required>
                                            <option value="Most played">Most played</option>
                                            <option value="Recommended">Recommended</option>
                                            <option value="Special Picks">Special Picks</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="game_price">Game Price</label>
                                        <input type="text" class="form-control" id="game_price" name="game_price" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add">Add Game</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal confirmation add -->
            <div class="modal fade" id="successInsertModal" tabindex="-1" role="dialog" aria-labelledby="successInsertModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successInsertModalLabel">Game Added Successfully</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    New game has been successfully added.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- table games -->
    <table id="gamesTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Game Name</th>
                <th>Game Category</th>
                <th>Game Company</th>
                <th>Size</th>
                <th>Release Date</th>
                <th>Rating</th>
                <th>Sector</th>
                <th>Game Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($games as $game) { ?>
                <tr>
                    <td><?php echo $game['game_id']; ?></td>
                    <td><?php echo $game['game_name']; ?></td>
                    <td><?php echo $game['game_category']; ?></td>
                    <td><?php echo $game['game_company']; ?></td>
                    <td><?php echo $game['size']; ?></td>
                    <td><?php echo $game['release_date']; ?></td>
                    <td><?php echo $game['rating']; ?></td>
                    <td><?php echo $game['Sector']; ?></td>
                    <td>Rp <?php echo number_format($game['game_price'], 2, ',', '.'); ?></td>
                    <td>
                        <form action="dashboard-admin.php" method="POST" style="display:inline-flex;">
                            <input type="hidden" name="id" value="<?php echo $game['game_id']; ?>">
                            <button type="button" class="btn btn-primary" style="margin-right: 20px;" onclick='openUpdateModal(<?php echo json_encode($game); ?>)'>Edit</button>
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this game?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Perbarui Game</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="../Action/action-update-games.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="update_id" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <div class="form-group">
                            <label for="update_game_name">Nama Game</label>
                            <input type="text" class="form-control" id="update_game_name" name="game_name" value="<?php echo htmlspecialchars($row['game_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_game_desc">Deskripsi Game</label>
                            <!-- Ganti textarea dengan input biasa -->
                            <input type="text" class="form-control" id="update_game_desc" name="game_desc" value="<?php echo htmlspecialchars($row['game_desc']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="update_game_category">Game Category</label>
                            <select class="form-control" id="update_game_category" name="game_category" required>
                                <option value="Action">Action</option>
                                <option value="Adventure">Adventure</option>
                                <option value="Simulation">Simulation</option>
                                <option value="Sports And Racing">Sports And Racing</option>
                                <option value="Strategy">Strategy</option>
                                <option value="Role-Playing">Role-Playing</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="update_game_company">Perusahaan Game</label>
                            <input type="text" class="form-control" id="update_game_company" name="game_company" value="<?php echo htmlspecialchars($row['game_company']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_size">Ukuran</label>
                            <input type="text" class="form-control" id="update_size" name="size" value="<?php echo htmlspecialchars($row['size']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_release_date">Tanggal Rilis</label>
                            <input type="date" class="form-control" id="update_release_date" name="release_date" value="<?php echo htmlspecialchars($row['release_date']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_rating">Peringkat</label>
                            <input type="text" class="form-control" id="update_rating" name="rating" value="<?php echo htmlspecialchars($row['rating']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_header">Header: </label>
                            <input type="file" class="form-control" id="update_header" name="header">
                            <?php if (isset($row['header']) && !empty($row['header'])) : ?>
                                <br>
                                <label for="current_header">Current Header:</label>
                                <img src="../images/game-images/header/<?= $row['header'] ?>" alt="Current Header" id="current_header" style="width: 100%; max-width: 200px;">
                                <!-- Set the value to the filename -->
                                <input type="hidden" name="existing_header" value="<?= $row['header'] ?>">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="update_photo1">Photo 1 : </label>
                            <input type="file" class="form-control" id="update_photo1" name="photo1">
                            <?php if (isset($row['photo1']) && !empty($row['photo1'])) : ?>
                                <br>
                                <label for="current_photo1">Current Photo 1:</label>
                                <img src="../images/game-images/header/<?= htmlspecialchars($row['photo1']) ?>" alt="Current Photo 1" id="current_photo1" style="width: 100%; max-width: 200px;">
                                <!-- Set the value to the filename -->
                                <input type="hidden" name="existing_photo1" value="<?= $row['photo1'] ?>">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="update_photo2">Foto 2 : </label>
                            <input type="file" class="form-control" id="update_photo2" name="photo2" accept="image/*">
                            <?php if (isset($row['photo2']) && !empty($row['photo2'])) : ?>
                                <br>
                                <label for="current_photo2">Current Photo 2:</label>
                                <img src="../images/game-images/photo2/<?= htmlspecialchars($row['photo2']) ?>" alt="Current Photo 2" id="current_photo2" style="width: 100%; max-width: 200px;">
                                <!-- Set the value to the filename -->
                                <input type="hidden" name="existing_photo2" value="<?= $row['photo2'] ?>">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="update_photo3">Foto 3 : </label>
                            <input type="file" class="form-control" id="update_photo3" name="photo3" accept="image/*">
                            <?php if (isset($row['photo3']) && !empty($row['photo3'])) : ?>
                                <br>
                                <label for="current_photo3">Current Photo 3:</label>
                                <img src="../images/game-images/photo3/<?= htmlspecialchars($row['photo3']) ?>" alt="Current Photo 3" id="current_photo3" style="width: 100%; max-width: 200px;">
                                <!-- Set the value to the filename -->
                                <input type="hidden" name="existing_photo3" value="<?= $row['photo3'] ?>">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="update_video">Video : </label>
                            <input type="file" class="form-control" id="update_video" name="video" accept="video/*">
                            <?php if (isset($row['video']) && !empty($row['video'])) : ?>
                                <br>
                                <label for="current_video">Current Video:</label>
                                <video controls style="width: 100%; max-width: 200px;">
                                    <source src="../images/game-images/video/<?= htmlspecialchars($row['video']) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <!-- Set the value to the filename -->
                                <input type="hidden" name="existing_video" value="<?= $row['video'] ?>">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="update_sector">Sector</label>
                            <select class="form-control" id="update_sector" name="sector" required>
                                <option value="Most played">Most played</option>
                                <option value="Recommended">Recommended</option>
                                <option value="Special Picks">Special Picks</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="update_game_price">Harga Game</label>
                            <input type="text" class="form-control" id="update_game_price" name="game_price" value="<?php echo htmlspecialchars($row['game_price']); ?>" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" name="update">Perbarui Game</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>




    <!-- modal confirmation update -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Update Successful</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Game details have been successfully updated.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }

        function confirmLogout() {
            $('#confirmLogoutModal').modal('show');
        }

        function logout() {
            window.location.href = 'login-admin.php';
        }
    </script>

    <!-- script for update -->
    <script>
        function openUpdateModal(game) {
            document.getElementById('update_id').value = game.game_id;
            document.getElementById('update_game_name').value = game.game_name;
            document.getElementById('update_game_desc').value = game.game_desc;
            document.getElementById('update_game_category').value = game.game_category;
            document.getElementById('update_game_company').value = game.game_company;
            document.getElementById('update_size').value = game.size;
            document.getElementById('update_release_date').value = game.release_date;
            document.getElementById('update_rating').value = game.rating;
            document.getElementById('update_sector').value = game.sector;
            document.getElementById('update_game_price').value = game.game_price;

            $('#updateModal').modal('show');
        }
        // <!-- modal for confirmation update -->
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('update_success')) {
                $('#successModal').modal('show');
            }
        });
    </script>
    <!-- script for modal add confirmation -->
    <script>
        $(document).ready(function() {
            // Check if the URL contains the message parameter
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');

            // If the message is "insert_success", show the success modal
            if (message === 'insert_success') {
                $('#successInsertModal').modal('show');
            }
        });

        $(document).ready(function() {
            $('#gamesTable').DataTable();
        });
    </script>

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Cek apakah tabel sudah memiliki inisialisasi DataTables sebelumnya
            if (!$.fn.DataTable.isDataTable('#gamesTable')) {
                // Jika belum, inisialisasikan DataTables
                $('#gamesTable').DataTable({
                    "paging": true, // Aktifkan pagination
                    "pagingType": "full_numbers", // Tipe pagination yang digunakan
                    "dom": 'lrtip' // Tampilkan pagination, info, dan pencarian
                });
            }
        });
    </script>
</body>

</html>