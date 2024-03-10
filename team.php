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
  
    $stmt2 = $conn->prepare("SELECT * from cars  where car_type=? group by car_name limit $offset,$total_records_per_page");
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
   $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM cars group by car_name");
  
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
  $stmt2 = $conn->prepare("SELECT * from cars group by car_name limit $offset,$total_records_per_page");
  $stmt2->execute();
  $products = $stmt2->get_result();



  }
  $stmt4 = $conn->prepare("SELECT * from team where 1 ");
$stmt4->execute();
$products2 = $stmt4->get_result();

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
        <h1 class="display-3 text-uppercase text-white mb-3">Notre équipe</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="">Acceuil</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Notre équipe</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="display-1 text-success text-center">04</h1>
            <h1 class="display-4 text-uppercase text-center mb-5">Notre Equipe</h1>
            <div class="owl-carousel team-carousel position-relative" style="padding: 0 30px;">
            <?php  while ($row = $products2->fetch_assoc()){ ?>
            <div class="team-item">
                    <img class="img-fluid w-100" src="assets/imgs/<?php echo $row['team_image']; ?>" alt="">
                    <div class="position-relative py-4">
                        <h4 class="text-uppercase"><?php  echo $row['team_name']; ?></h4>
                        <p class="m-0"><?php  echo $row['team_fonction']; ?></p>
                        <div class="team-social position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <?php  } ?>
                
               
            </div>
        </div>
    </div>
    <!-- Team End -->


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