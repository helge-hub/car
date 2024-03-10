<?php 

session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $stmt1 = $conn->prepare("DELETE from team where team_id = ?");

    $stmt1->bind_param('i',$product_id);
  
    if($stmt1->execute()){

        header('location: team.php?deleted_successfully=team has been deleted successfully');
    }else{
        header('location: team.php?deleted_failure=Could not delete product');
    }

}


?>