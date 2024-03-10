<?php 
session_start();
include('server/connection.php');
if(isset($_GET['id'])){

    $car_id = $_GET['id'];

    $stmt1 = $conn->prepare("SELECT * FROM cars WHERE car_id=? LIMIT 1");
  $stmt1->bind_param("i",$car_id);

  $stmt1->execute();
  $cars1 = $stmt1->get_result();
  while ($row = $cars1->fetch_assoc()){
    $car_price = $row['car_price'];
    $reservation_date_drop=$row['reservation_date_drop'];
  }

    $stmt2 = $conn->prepare("SELECT * FROM reservation WHERE car_id=? and reservation_status='encours' and user_id=? LIMIT 1");
  $stmt2->bind_param("ii",$car_id,$_SESSION['user_id']);

  $stmt2->execute();
  $cars2 = $stmt2->get_result();
  while ($row = $cars2->fetch_assoc()){
   $car_duration=$row['duration'];
   $amount = $row['amount'];
  }


  $id_transaction = rand();
  $total = $car_price*$car_duration;
  $_SESSION['sub']=$amount;
  $_SESSION['trans']=$id_transaction;
  $_SESSION['car_date_drop']=$reservation_date_drop;
  $_SESSION['car_id']=$car_id;

  header('location:singpay.php');

}


?>