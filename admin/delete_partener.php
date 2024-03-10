<?php 

session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $stmt1 = $conn->prepare("DELETE from partener where partener_id = ?");

    $stmt1->bind_param('i',$product_id);
  
    if($stmt1->execute()){

        header('location: partener.php?deleted_successfully=Partener has been deleted successfully');
    }else{
        header('location: partener.php?deleted_failure=Could not delete this location');
    }

}


?>