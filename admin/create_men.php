<?php

include('../server/connection.php');

if(isset($_POST['create_product'])){


    $name= $_POST['name'];//name car
    $fonction = $_POST['fonction'];//model car
    
    



    $image1 = $_FILES['image1']['tmp_name'];
   

   // $file_name = $_FILES['image1']['name'];

    $image_name1 = $name."1.jpg";
   


    move_uploaded_file($image1,"../assets/imgs/".$image_name1);
    

    $stmt = $conn->prepare("INSERT INTO team (team_name, team_fonction,team_image)
                                                  VALUES(?,?,?)");

    $stmt->bind_param('sss',$name,$fonction,$image_name1);

    if($stmt->execute()){
        header('location:team.php?product_created=team has been created successfully');
    }else{
        header('location:team.php?product_failed=team Error occured, try again');
    }
}




?>