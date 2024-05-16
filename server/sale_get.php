<?php
    include('connection.php');
    $query_sale = "SELECT *, (game_price * 0.7) AS price FROM game WHERE sector = 'SALE'";

    $stmt_sale = $conn ->prepare($query_sale);

    $stmt_sale->execute();

    $sale = $stmt_sale->get_result();
?>