<?php 

session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $comment_status = 1;
    $stmt1 = $conn->prepare("UPDATE comment set comment_status=? where comment_id = ?");

    $stmt1->bind_param('ii',$comment_status,$product_id);
  
    if($stmt1->execute()){

        header('location: products.php?deleted_successfully=comment has been display successfully');
    }else{
        header('location: products.php?deleted_failure=Could not display comment');
    }

}


?>