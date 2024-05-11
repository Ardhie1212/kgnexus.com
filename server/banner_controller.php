<?php
    include('connection.php');
    $query_banner = "SELECT header from game WHERE sector = 'Banner' LIMIT 5";

    $stmt_banner = $conn ->prepare($query_banner);

    $stmt_banner->execute();

    $banner_image = $stmt_banner->get_result();
?>