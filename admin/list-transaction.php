<?php
include('../server/connection.php');

$query_view = "SELECT t.transaction_id, u.email, u.username, g.game_name, g.game_company, g.game_price, t.status 
               FROM transaction t 
               JOIN game g ON g.game_id = t.game_id
               JOIN user u ON u.id_user = t.id_user";
$result = mysqli_query($conn, $query_view);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Table</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
        }

        .header-container h1 {
            flex-grow: 1;
            margin: 0;
        }

        .hamburger {
            cursor: pointer;
            font-size: 30px;
        }
    </style>
</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="list-transaction.php">List Transaksi</a>
        <a href="dashboard-admin.php">List Games</a>
        <a href="#" onclick="confirmLogout()">Logout</a>

        <!-- Modal for Logout Confirmation -->
        <div class="modal fade" id="confirmLogoutModal" tabindex="-1" role="dialog" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true" style="z-index: 1050;">
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
    </div>

    <div id="main">
        <div class="header-container">
            <h1>Transaction</h1>
            <div class="hamburger" onclick="openNav()">&#9776;</div>
        </div>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID Transaction</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Game</th>
                    <th>Company</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['transaction_id'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['game_name'] . "</td>";
                        echo "<td>" . $row['game_company'] . "</td>";
                        echo "<td>" . $row['game_price'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo '<td>
                                <form class="status-form" method="POST" action="update_status_admin.php">
                                    <input type="hidden" name="transaction_id" value="' . $row['transaction_id'] . '">
                                    <input type="checkbox" name="status" value="verified" class="status-checkbox" ' . ($row['status'] == 'verified' ? 'checked disabled' : '') . '>
                                </form>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Status Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to change the status to "verified"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentForm;
            var currentCheckbox;

            $('.status-checkbox').change(function() {
                if (this.checked) {
                    currentForm = $(this).closest('form');
                    currentCheckbox = this;
                    $('#confirmModal').modal('show');
                }
            });

            $('#confirmBtn').click(function() {
                $('#confirmModal').modal('hide');
                if (currentForm) {
                    currentForm.submit();
                }
            });

            $('#confirmModal').on('hidden.bs.modal', function () {
                if (!$('#confirmBtn').data('confirmed')) {
                    $(currentCheckbox).prop('checked', false);
                }
            });

            $('#confirmBtn').click(function() {
                $(this).data('confirmed', true);
                if (currentForm) {
                    currentForm.submit();
                }
            });

            $('#confirmModal').on('hidden.bs.modal', function() {
                $('#confirmBtn').data('confirmed', false);
            });
        });

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
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
