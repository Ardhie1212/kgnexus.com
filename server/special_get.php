<?php
    include('connection.php');
    $query_special = "SELECT * FROM game WHERE sector = 'Special Picks'";

    $stmt_special = $conn ->prepare($query_special);

    $stmt_special->execute();

    $specials = $stmt_special->get_result();
?>