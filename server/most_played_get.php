<?php
include('connection.php');

$query_mostplayed = "SELECT * FROM game WHERE Sector = 'Most played'";

$stmt_mostplayed = $conn ->prepare($query_mostplayed);

$stmt_mostplayed->execute();

$mostplayed = $stmt_mostplayed->get_result();

?>