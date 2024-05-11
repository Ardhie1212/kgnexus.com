<?php
    include('connection.php');
    $query_recommended_image = "SELECT photo1, game_price, game_name, rating from game WHERE sector = 'recommended'";

    $stmt_recommended = $conn ->prepare($query_recommended_image);

    $stmt_recommended->execute();

    $recommended = $stmt_recommended->get_result();
?>