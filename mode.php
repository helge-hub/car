<?php
include('server/connection.php');
$reservation_id = $_GET['mode'];
$id = $_GET['id'];
if($id==1){
    $mode ="airtel money";
    $stmt = $conn->prepare("UPDATE reservation set mode=? WHERE reservation_id=?");

$stmt->bind_param('si',$mode,$reservation_id);

if($stmt->execute()){
    header('location:index.php');
}else{
    header('location:index.php');
}
}
if($id==2){
    $mode ="moov money";
    $stmt = $conn->prepare("UPDATE reservation set mode=? WHERE reservation_id=?");

$stmt->bind_param('si',$mode,$reservation_id);

if($stmt->execute()){
    header('location:index.php');
}else{
    header('location:index.php');
}
}
if($id==3){
    $mode ="credit card";
    $stmt = $conn->prepare("UPDATE reservation set mode=? WHERE reservation_id=?");

$stmt->bind_param('si',$mode,$reservation_id);

if($stmt->execute()){
    header('location:index.php');
}else{
    header('location:index.php');
}
}



?>