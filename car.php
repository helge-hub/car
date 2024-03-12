<?php
include("server/connection.php");


if(isset($_POST['search'])){

    if(isset($_GET['page_no'])  && $_GET['page_no'] != ""){
      $page_no = $_GET['page_no'];
     }else{
      $page_no = 1;
     }
     
  
      $category = $_POST['category'];
      $stmt1 = $conn->prepare('SELECT COUNT(*) AS total_records FROM cars  where car_type=? group by car_name');
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
  
    $stmt2 = $conn->prepare("SELECT * from cars  where car_type=?  limit $offset,$total_records_per_page");
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
   $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM cars");
  
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
        <h1 class="display-3 text-uppercase text-white mb-3">Catalogue voiture</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="">Acceuil</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Catalogue Voiture</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <!-- Rent A Car Start -->
    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <h1 class="display-4 text-uppercase text-center mb-5">Trouver votre voiture</h1>
            <div class="row">
            <?php  while ($row = $products->fetch_assoc()){ ?>
                
                    <!-- get id car for each car -->
                    <?php   $car_id = $row['car_id'] ;?>

                    <!-- get max id fr car rent -->

                    <?php    

                    $stmt4 = $conn->prepare("SELECT MAX(location.location_price)as maxPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
                    $stmt4->bind_param("i",$car_id);
                    $stmt4->execute();
                    $car_id_max = $stmt4->get_result();
                      while ($row1 = $car_id_max->fetch_assoc()){
                        $maxPrice = $row1['maxPrice'];
                      }

                    $stmt5 = $conn->prepare("SELECT MIN(location.location_price)as minPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
                    $stmt5->bind_param("i",$car_id);
                    $stmt5->execute();
                    $car_id_min = $stmt5->get_result();
                      while ($row2 = $car_id_min->fetch_assoc()){
                        $minPrice = $row2['minPrice'];
                      }


                    

                    ?>




                <div onclick="window.location.href='detail.php?id=<?php echo $row['car_id']; ?>&category=<?php echo $row['car_type']; ?>';" class="col-lg-4 col-md-6 mb-2">
                    <div class="rent-item mb-4">
                        <img class="img-fluid mb-4" src="assets/imgs/<?php echo $row['car_image1']; ?>" alt="">
                        <h4 class="text-uppercase mb-4"><?php  echo $row['car_name'];  ?></h4>
                        <div class="d-flex justify-content-center mb-4">
                            <div class="px-2">
                                <i class="fa fa-car text-success mr-1"></i>
                                <span><?php echo $row['an'];  ?></span>
                            </div>
                            <div class="px-2 border-left border-right">
                                <i class="fa fa-cogs text-success mr-1"></i>
                                <span><?php   echo $row['trans']; ?></span>
                            </div>
                            <div class="px-2">
                                <i class="fa fa-road text-success mr-1"></i>
                                <span><?php echo number_format($row['car_km']);  ?>Km</span>
                            </div>
                        </div>
                        <a class="btn btn-success px-3" href="detail.php?id=<?php echo $row['car_id']; ?>&category=<?php echo $row['car_type']; ?>"><?php echo number_format($minPrice);   ?> - <?php echo number_format($maxPrice);   ?> CFA /Jour</a>
                    </div>
                </div>
            <?php  } ?>

                <!-- <div class="col-lg-4 col-md-6 mb-2">
                    <div class="rent-item active mb-4">
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
                </div>
                <div class="col-lg-4 col-md-6 mb-2">
                    <div class="rent-item mb-4">
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
                </div>
                <div class="col-lg-4 col-md-6 mb-2">
                    <div class="rent-item mb-4">
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
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-2">
                    <div class="rent-item mb-4">
                        <img class="img-fluid mb-4" src="img/car-rent-5.png" alt="">
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
                </div>
                <div class="col-lg-4 col-md-6 mb-2">
                    <div class="rent-item mb-4">
                        <img class="img-fluid mb-4" src="img/car-rent-6.png" alt="">
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
                </div> -->
            </div>
             <!-- Rent A Car End -->
    <nav aria-label="Page navigation example">
                <ul class="pagination mt-5 mx-auto">
                    <li class="page-item <?php if($page_no<=1){echo 'disabled'; }  ?>">
                      <a href="<?php if($page_no<=1){echo '#';}else{echo "?page_no=".($page_no-1);} ?>" class="page-link">Précédement</a>
                    </li>
                    <li class="page-item"><a href="?page_no=1" class="page-link">1</a></li>
                    <li class="page-item"><a href="?page_no=2" class="page-link">2</a></li>
                    
                    <?php if($page_no >=3){ ?>
                    <li class="page-item"><a href="#" class="page-link">...</a></li>
                    <li class="page-item"><a href="<?php echo  "?page_no=".$page_no ;?>" class="page-link"><?php echo $page_no;  ?></a></li>
                     
                    <?php  } ?>

                    <li class="page-item <?php if($page_no>= $total_no_of_pages){echo 'disabled'; } ?>">
                      <a href="<?php if($page_no>=$total_no_of_pages){ echo '#';}else{echo "?page_no=".($page_no+1); }  ?>" class="page-link">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
   


    <!-- Banner Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row mx-0">
                <div class="col-lg-6 px-0">
                    <div class="px-5 bg-secondary d-flex align-items-center justify-content-between" style="height: 350px;">
                        <img class="img-fluid flex-shrink-0 ml-n5 w-50 mr-4" src="img/banner-left.png" alt="">
                        <div class="text-right">
                            <h3 class="text-uppercase text-light mb-3">Veux-tu être chauffeur?</h3>
                            <p class="mb-4">Lorem justo sit sit ipsum eos lorem kasd, kasd labore</p>
                            <a class="btn btn-success py-2 px-4" href="">Commencer</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 px-0">
                    <div class="px-5 bg-dark d-flex align-items-center justify-content-between" style="height: 350px;">
                        <div class="text-left">
                            <h3 class="text-uppercase text-light mb-3">Recherchez-vous une voiture?</h3>
                            <p class="mb-4">Lorem justo sit sit ipsum eos lorem kasd, kasd labore</p>
                            <a class="btn btn-success py-2 px-4" href="">Commencer </a>
                        </div>
                        <img class="img-fluid flex-shrink-0 mr-n5 w-50 ml-4" src="img/banner-right.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->


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