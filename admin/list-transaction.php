<?php
include('../server/connection.php');

// Definisikan variabel halaman saat ini dan jumlah item per halaman
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$items_per_page = 10; // Ubah sesuai kebutuhan Anda

// Hitung offset untuk query database
$offset = ($page - 1) * $items_per_page;

$query_view = "SELECT SQL_CALC_FOUND_ROWS t.transaction_id, u.email, u.username, g.game_name, g.game_company, g.game_price, t.status 
               FROM transaction t 
               JOIN game g ON g.game_id = t.game_id
               JOIN user u ON u.id_user = t.id_user
               LIMIT ?, ?";
$stmt = $conn->prepare($query_view);
$stmt->bind_param('ii', $offset, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Dapatkan total jumlah baris yang ditemukan tanpa batasan limit
$total_rows_result = $conn->query("SELECT FOUND_ROWS() as total_rows");
$total_rows = $total_rows_result->fetch_assoc()['total_rows'];

// Hitung total halaman berdasarkan jumlah item per halaman
$total_pages = ceil($total_rows / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Table</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background-color: #FFFAE6;
        }
        .sidebar {
            height: 100%;
            width: 250px;
            /* Lebar awal sidebar */
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

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #111;
            color: white;
            padding: 10px 15px;
            border: none;
        }

        .openbtn:hover {
            background-color: #444;
        }

        #main {
            transition: margin-left .5s;
            padding: 16px;
        }

        .header-container {
            display: flex;
            align-items: center;
            background-color: #111;
            color: white;
            padding: 25px;
            justify-content: space-between;
        }

        .header-container h1 {
            flex-grow: 1;
            margin: 0;
        }

        .hamburger {
            cursor: pointer;
            font-size: 30px;
            margin-right: 500px;
        }

        /* Media queries untuk sidebar responsif */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 0;
                /* Lebar sidebar menjadi 0 ketika tampilan di layar kecil */
            }

            #main {
                margin-left: 0;
                /* Margin utama dihapus untuk mengakomodasi lebar sidebar yang berubah */
            }
        }
    </style>
</head>

<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="list-transaction.php">List Transaksi</a>
        <a href="dashboard-admin.php">List Games</a>
        <a href="#" onclick="confirmLogout()">Logout</a>
    </div>
    <div id="main">
        <button class="openbtn" onclick="openNav()">&#9776;</button><i class="bi bi-list"></i>
        <div class="container">
            <h1>Transaction Table</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Game Name</th>
                        <th>Game Company</th>
                        <th>Game Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['game_name']) ?></td>
                                <td><?= htmlspecialchars($row['game_company']) ?></td>
                                <td><?= htmlspecialchars($row['game_price']) ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                                <td>
                                    <form method="POST" action="../Action/update_status_admin.php">
                                        <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($row['transaction_id']) ?>">
                                        <button type="submit" name="confirm_refund" class="btn btn-warning" value="Confirm Refund">Confirm Refund</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">No records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

</body>

</html>