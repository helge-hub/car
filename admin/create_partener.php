<?php

include('../server/connection.php');

if(isset($_POST['create_product'])){


    $partener_name = $_POST['partener_name'];//name car
    $partener_email = $_POST['partener_email'];//model car
    $partener_phone = $_POST['partener_phone'];//car type
    



    $image1 = $_FILES['partener_logo']['tmp_name'];
    

   // $file_name = $_FILES['image1']['name'];

    $image_name1 = $partener_name."1.jpg";
   


    move_uploaded_file($image1,"../assets/imgs/".$image_name1);
   

    $stmt = $conn->prepare("INSERT INTO partener (partener_name, partener_email, partener_phone, partener_logo)
                                                  VALUES(?,?,?,?)");

    $stmt->bind_param('ssss',$partener_name,$partener_email,$partener_phone,$image_name1);

    if($stmt->execute()){
        header('location:partener.php?product_created=Partener has been created successfully');
    }else{
        header('location:partener.php?product_failed=Partener Error occured, try again');
    }
}




?>