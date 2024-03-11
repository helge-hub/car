<?php  
session_start();
include('server/connection.php');
if(isset($_GET['id'])){

  $car_id = $_GET['id'];
  $car_category = $_GET['category'];
  $stmt = $conn->prepare("SELECT * FROM cars WHERE car_id=? LIMIT 1");
  $stmt->bind_param("i",$car_id);

  $stmt->execute();
  
  $cars = $stmt->get_result();
  

            $stmt5 = $conn->prepare("SELECT * FROM cars WHERE car_type=? ORDER BY RAND()  limit 4 ");
            $stmt5->bind_param("s",$car_category);

            $stmt5->execute();

            $products41 = $stmt5->get_result();


}else{
  header('location:car.php');
}

if(isset($_POST['btn_check'])){

    $stmt6 = $conn->prepare("SELECT * FROM cars WHERE car_id=?");
    $stmt6->bind_param("i",$car_id);

    $stmt6->execute();

    $cars_c = $stmt6->get_result();

    while($row = $cars_c->fetch_assoc()){

       $car_drop_date =$row['car_drop_date']; 

    }

    $date_pickup = $_POST['date_pickup'];

    if(!$car_drop_date==0){
        $date_valid =date("d/m/Y", strtotime($car_drop_date));
        header('location: detail.php?id='.$car_id.'&category='.$car_category.'&error=Disponible le :'.$date_valid );  
    }else{
        header('location: detail.php?id='.$car_id.'&category='.$car_category.'&error=Disponible maintenant');
    }

}



if(isset($_POST['search'])){

    if(isset($_GET['page_no'])  && $_GET['page_no'] != ""){
      $page_no = $_GET['page_no'];
     }else{
      $page_no = 1;
     }
     
  
      $category = $_POST['category'];
      $stmt1 = $conn->prepare('SELECT COUNT(*) AS total_records FROM cars  where car_type=?');
      $stmt1->bind_param('s',$category);
      $stmt1->execute();
      $stmt1->bind_result($total_records);
      $stmt1->store_result();
      $stmt1->fetch();
    $total_records_per_page = 20;
  
    $offset =($page_no-1) * $total_records_per_page;
  
    $previous_page = $page_no -1;
    $next_page = $page_no +1;
  
    $adjcents = "2";
  
    $total_no_of_pages = ceil($total_records/$total_records_per_page);
  
    $stmt2 = $conn->prepare("SELECT * from cars  where car_type=? limit $offset,$total_records_per_page");
    $stmt2->bind_param("s",$category);
    $stmt2->execute();
    $products = $stmt2->get_result();
  
    
  
  
  
    //return all products
  }else{

   if(isset($_GET['page_no'])  && $_GET['page_no'] != ""){
    $page_no = $_GET['page_no'];
   }else{
    $page_no = 1;
   }
  
  // return number of products 
   $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM cars ");
  
    $stmt1->execute();
  
    $stmt1->bind_result($total_records);
  
    $stmt1->store_result();
  
    $stmt1->fetch();
  
    //3. product per page
    $total_records_per_page = 20;
  
    $offset =($page_no-1) * $total_records_per_page;
  
    $previous_page = $page_no -1;
    $next_page = $page_no +1;
  
    $adjcents = "2";
  
    $total_no_of_pages = ceil($total_records/$total_records_per_page);
  
    
  
  //4. gets all products
  $stmt2 = $conn->prepare("SELECT * from cars  limit $offset,$total_records_per_page");
  $stmt2->execute();
  $products = $stmt2->get_result();



  }





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ROYAL CARS - Car Rental HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
      <!-- Topbar Start -->
      <?php  include('topbar.php') ;  ?>
    <!-- Topbar End -->


    <!-- Navbar Start -->
   <?php  include('header.php') ;?>
    <!-- Navbar End -->



    <!-- Search Start -->
    <?php include('search.php'); ?>
    <!-- Search End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header">
        <h1 class="display-3 text-uppercase text-white mb-3">Detail voiture</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="">Acceuil</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0"> Detail voiture</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <!-- Detail Start -->
    <div class="container-fluid pt-5">
        <div class="container pt-5">
            <div class="row">
                <div class="col-lg-8 mb-5">
                    <?php  while($row = $cars->fetch_assoc()){?>
                    <h1 class="display-4 text-uppercase mb-5"><?php echo $row['car_name']; ?></h1>
                    <div class="row mx-n2 mb-3">
                        <div class="col-md-3 col-6 px-2 pb-2">
                            <img class="img-fluid w-100" src="assets/imgs/<?php echo $row['car_image1']; ?>" alt="">
                        </div>
                        <div class="col-md-3 col-6 px-2 pb-2">
                            <img class="img-fluid w-100" src="assets/imgs/<?php echo $row['car_image2']; ?>" alt="">
                        </div>
                        <div class="col-md-3 col-6 px-2 pb-2">
                            <img class="img-fluid w-100" src="assets/imgs/<?php echo $row['car_image3']; ?>" alt="">
                        </div>
                        <div class="col-md-3 col-6 px-2 pb-2">
                            <img class="img-fluid w-100" src="assets/imgs/<?php echo $row['car_image4']; ?>" alt="">
                        </div>
                    </div>
                    <p><?php  echo $row['car_model']; ?></p>
                    <div class="row pt-2">
                        <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-car text-success mr-2"></i>
                            <span>Model: <?php echo $row['an'] ;?></span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-cogs text-success mr-2"></i>
                            <span><?php  echo $row['trans'] ?></span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-road text-success mr-2"></i>
                            <span><?php echo number_format($row['car_km']);  ?></span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-eye text-success mr-2"></i>
                            <span>GPS: <?php echo $row['car_gps'];  ?></span>
                        </div>
                        
                    </div>
                    <div class="col">

                        <?php   
                            $stmt4 = $conn->prepare("SELECT * from workspace left join location on workspace.location_id = location.location_id left join cars on workspace.car_id = cars.car_id  where cars.car_id=? ");
                            $stmt4->bind_param("i",$car_id);
                            $stmt4->execute();
                            $products4 = $stmt4->get_result();
                        ?>
                        <?php  while($row1 = $products4->fetch_assoc()){?>
                        <div class="row">
                            <div class="col">Lieu : <?php  echo $row1['location_name'] ;?></div>
                            <div class="col">Prix : <?php   echo $row1['location_price']; ?> CFA / Jour</div>
                        </div>
                        <?php  } ;?>


                    </div>
                    <?php  } ?>
               </div>

                <div class="col-lg-4 mb-5">
                    <div class="bg-secondary p-5">
                        <h3 class="text-primary text-center mb-4">Vérification disponibilité</h3>
                        <p style="color:green;"><?php if(isset($_GET['error'])){ echo $_GET['error']; } ?></p>
                        <form action="" method="post">
                        <!-- <div class="form-group">
                            <select class="custom-select px-4" style="height: 50px;">
                                <option selected>Pickup Location</option>
                                <option value="1">Location 1</option>
                                <option value="2">Location 2</option>
                                <option value="3">Location 3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="custom-select px-4" style="height: 50px;">
                                <option selected>Drop Location</option>
                                <option value="1">Location 1</option>
                                <option value="2">Location 2</option>
                                <option value="3">Location 3</option>
                            </select>
                        </div> -->
                        <div class="form-group">
                            <div class="date" id="date1" data-target-input="nearest">
                                <input type="text" name="date_pickup" class="form-control p-4 datetimepicker-input" placeholder="Date de récupération"
                                    data-target="#date1" data-toggle="datetimepicker" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="time" id="time1" data-target-input="nearest">
                                <input type="text" class="form-control p-4 datetimepicker-input" placeholder="Pickup Time"
                                    data-target="#time1" data-toggle="datetimepicker" required>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <select class="custom-select px-4" style="height: 50px;">
                                <option selected>Select Person</option>
                                <option value="1">Person 1</option>
                                <option value="2">Person 2</option>
                                <option value="3">Person 3</option>
                            </select>
                        </div> -->
                        <div class="form-group mb-0">
                            <button class="btn btn-primary btn-block" type="submit" name="btn_check" style="height: 50px;">Vérifier</button>
                        </div>
                        <div class="form-group mb-0 mt-2">
                            <?php if(isset($_GET['error'])){?>
                                
                                <a href="booking.php?id=<?php echo $car_id; ?>" class="btn btn-success btn-block"  style="height: 50px;">Rerserver maintenant</a>
                                <?php  }?>
                                
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Detail End -->


    <!-- Related Car Start -->
    <div class="container-fluid pb-5">
        <div class="container pb-5">
            <h2 class="mb-4">Voitures associées</h2>
            <div class="owl-carousel related-carousel position-relative" style="padding: 0 30px;">
            <?php   while ($row61 = $products41->fetch_assoc()){  ?>

                <?php   $car_id11 = $row61['car_id'] ;?>

<!-- get max id fr car rent -->

<?php    

$stmt41 = $conn->prepare("SELECT MAX(location.location_price)as maxPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
$stmt41->bind_param("i",$car_id11);
$stmt41->execute();
$car_id_max = $stmt41->get_result();
  while ($row11 = $car_id_max->fetch_assoc()){
    $maxPrice = $row11['maxPrice'];
  }

$stmt51 = $conn->prepare("SELECT MIN(location.location_price)as minPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
$stmt51->bind_param("i",$car_id11);
$stmt51->execute();
$car_id_min = $stmt51->get_result();
  while ($row21 = $car_id_min->fetch_assoc()){
    $minPrice = $row21['minPrice'];
  }




?>

                <div class="rent-item">
                    <img class="img-fluid mb-4" src="assets/imgs/<?php echo $row61['car_image1'];  ?>" alt="">
                    <h4 class="text-uppercase mb-4"><?php echo $row61['car_name'] ;?></h4>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="px-2">
                            <i class="fa fa-car text-success mr-1"></i>
                            <span><?php echo $row61['an'] ;?></span>
                        </div>
                        <div class="px-2 border-left border-right">
                            <i class="fa fa-cogs text-success mr-1"></i>
                            <span><?php echo $row61['trans'] ;?></span>
                        </div>
                        <div class="px-2">
                            <i class="fa fa-road text-success mr-1"></i>
                            <span><?php echo number_format($row61['car_km']);  ?> km</span>
                        </div>
                    </div>
                    <a class="btn btn-success px-3" href="detail.php?id=<?php echo $row61['car_id']; ?>&category=<?php echo $row61['car_type']; ?>"><?php echo number_format($minPrice);  ?>- <?php echo number_format($maxPrice);  ?>  CFA /Jour</a>
                </div>



                <?php  } ?>
                <!-- <div class="rent-item">
                    <img class="img-fluid mb-4" src="img/car-rent-2.png" alt="">
                    <h4 class="text-uppercase mb-4">Mercedes Benz R3</h4>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="px-2">
                            <i class="fa fa-car text-primary mr-1"></i>
                            <span>2015</span>
                        </div>
                        <div class="px-2 border-left border-right">
                            <i class="fa fa-cogs text-primary mr-1"></i>
                            <span>AUTO</span>
                        </div>
                        <div class="px-2">
                            <i class="fa fa-road text-primary mr-1"></i>
                            <span>25K</span>
                        </div>
                    </div>
                    <a class="btn btn-primary px-3" href="">$99.00/Day</a>
                </div>
                <div class="rent-item">
                    <img class="img-fluid mb-4" src="img/car-rent-3.png" alt="">
                    <h4 class="text-uppercase mb-4">Mercedes Benz R3</h4>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="px-2">
                            <i class="fa fa-car text-primary mr-1"></i>
                            <span>2015</span>
                        </div>
                        <div class="px-2 border-left border-right">
                            <i class="fa fa-cogs text-primary mr-1"></i>
                            <span>AUTO</span>
                        </div>
                        <div class="px-2">
                            <i class="fa fa-road text-primary mr-1"></i>
                            <span>25K</span>
                        </div>
                    </div>
                    <a class="btn btn-primary px-3" href="">$99.00/Day</a>
                </div>
                <div class="rent-item">
                    <img class="img-fluid mb-4" src="img/car-rent-4.png" alt="">
                    <h4 class="text-uppercase mb-4">Mercedes Benz R3</h4>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="px-2">
                            <i class="fa fa-car text-primary mr-1"></i>
                            <span>2015</span>
                        </div>
                        <div class="px-2 border-left border-right">
                            <i class="fa fa-cogs text-primary mr-1"></i>
                            <span>AUTO</span>
                        </div>
                        <div class="px-2">
                            <i class="fa fa-road text-primary mr-1"></i>
                            <span>25K</span>
                        </div>
                    </div>
                    <a class="btn btn-primary px-3" href="">$99.00/Day</a>
                </div> -->
            </div>
        </div>
    </div>
    <!-- Related Car End -->


    <!-- Vendor Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="owl-carousel vendor-carousel">
                <div class="bg-light p-4">
                    <img src="img/vendor-1.png" alt="">
                </div>
                <div class="bg-light p-4">
                    <img src="img/vendor-2.png" alt="">
                </div>
                <div class="bg-light p-4">
                    <img src="img/vendor-3.png" alt="">
                </div>
                <div class="bg-light p-4">
                    <img src="img/vendor-4.png" alt="">
                </div>
                <div class="bg-light p-4">
                    <img src="img/vendor-5.png" alt="">
                </div>
                <div class="bg-light p-4">
                    <img src="img/vendor-6.png" alt="">
                </div>
                <div class="bg-light p-4">
                    <img src="img/vendor-7.png" alt="">
                </div>
                <div class="bg-light p-4">
                    <img src="img/vendor-8.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor End -->


    <!-- Footer Start -->
    
    <!-- Footer Start -->
   <?php include('footer.php') ; ?>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>