<?php

include('../server/connection.php');

if(isset($_POST['create_product'])){


    $car_name = $_POST['name'];//name car
    $car_description = $_POST['description'];//model car
    $car_type = $_POST['category'];//car type
    $car_km = $_POST['km'];//car km
    $car_gps = $_POST['gps'];// car gps
    $car_price = $_POST['price'];//car price
    $car_an = $_POST['an'];//car price
    $car_trans = $_POST['trans'];//car price
    $car_date_drop="0" ;
    $car_status="dispo";
    $car_autoradio =$_POST['car_autoradio'];
    $car_sit = $_POST['car_sit'];
    $partener_id = $_POST['partener_id'];
    



    $image1 = $_FILES['image1']['tmp_name'];
    $image2 = $_FILES['image2']['tmp_name'];
    $image3 = $_FILES['image3']['tmp_name'];
    $image4 = $_FILES['image4']['tmp_name'];

   // $file_name = $_FILES['image1']['name'];

    $image_name1 = $car_name."1.jpg";
    $image_name2 = $car_name."2.jpg";
    $image_name3 = $car_name."3.jpg";
    $image_name4 = $car_name."4.jpg";


    move_uploaded_file($image1,"../assets/imgs/".$image_name1);
    move_uploaded_file($image2,"../assets/imgs/".$image_name2);
    move_uploaded_file($image3,"../assets/imgs/".$image_name3);
    move_uploaded_file($image4,"../assets/imgs/".$image_name4);

    $stmt = $conn->prepare("INSERT INTO cars (car_name, car_model, car_type, car_km, car_gps, car_image1, car_image2, car_image3, car_image4, car_drop_date, car_status, car_price,an,trans,car_autoradio,car_sit,partener_id)
                                                  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param('sssssssssssisssis',$car_name,$car_description,$car_type,$car_km,$car_gps,$image_name1,$image_name2,$image_name3,$image_name4,$car_date_drop,$car_status,$car_price,$car_an,$car_trans,$car_autoradio,$car_sit,$partener_id);

    if($stmt->execute()){
        header('location:products.php?product_created=Product has been created successfully');
    }else{
        header('location:products.php?product_failed=Product Error occured, try again');
    }
}




?>