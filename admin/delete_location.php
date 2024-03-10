<?php 

session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $stmt1 = $conn->prepare("DELETE from location where location_id = ?");

    $stmt1->bind_param('i',$product_id);
  
    if($stmt1->execute()){

        header('location: table_location.php?deleted_successfully=Location has been deleted successfully');
    }else{
        header('location: table_location.php?deleted_failure=Could not delete this location');
    }

}


?>