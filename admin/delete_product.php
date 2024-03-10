<?php 

session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $stmt1 = $conn->prepare("DELETE from car where car_id = ?");

    $stmt1->bind_param('i',$product_id);
  
    if($stmt1->execute()){

        header('location: products.php?deleted_successfully=Product has been deleted successfully');
    }else{
        header('location: products.php?deleted_failure=Could not delete product');
    }

}


?>