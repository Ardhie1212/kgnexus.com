<?php
include('connection.php');

$query_mostplayed = "SELECT * FROM game";

$stmt_mostplayed = $conn ->prepare($query_mostplayed);

$stmt_mostplayed->execute();

$mostplayed = $stmt_mostplayed->get_result();

?>