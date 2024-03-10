<?php
include('connection.php');
$stmt = $conn->prepare("SELECT * FROM cars LIMIT 4");
$stmt->execute();
$cars = $stmt->get_result();
?>