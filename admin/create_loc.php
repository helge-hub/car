<?php

include('../server/connection.php');

if(isset($_POST['create_product'])){


    $name= $_POST['name'];//name car
    $fonction = $_POST['fonction'];//model car
    

    $stmt = $conn->prepare("INSERT INTO location (location_name, location_price)
                                                  VALUES(?,?)");

    $stmt->bind_param('si',$name,$fonction);

    if($stmt->execute()){
        header('location:table_location.php?product_created=location has been created successfully');
    }else{
        header('location:table_location.php?product_failed=location Error occured, try again');
    }
}




?>