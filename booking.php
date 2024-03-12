<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('server/connection.php');
if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
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
    $stmt2 = $conn->prepare("SELECT * from cars limit $offset,$total_records_per_page");
    $stmt2->execute();
    $products = $stmt2->get_result();
  
  
  
    }
  
  
  
if(isset($_GET['id'])){

  $car_id = $_GET['id'];
//   $car_category = $_GET['category'];
  $stmt = $conn->prepare("SELECT * FROM cars WHERE car_id=? LIMIT 1");
  $stmt->bind_param("i",$car_id);

  $stmt->execute();
  $cars = $stmt->get_result();
  $stmt1 = $conn->prepare("SELECT * FROM cars WHERE car_id=? LIMIT 1");
  $stmt1->bind_param("i",$car_id);

  $stmt1->execute();
  $cars1 = $stmt1->get_result();
  while ($row = $cars1->fetch_assoc()){
    $car_name = $row['car_name'];
  }

}else{
  header('location:car.php');
}

if(isset($_POST['book_btn'])){


    $reservation_date = date("Ymd");;//date du jour
    
    $reservation_pickup1 = $_POST['reservation_date'];
    $reservation_pickup= date("Ymd", strtotime($reservation_pickup1));
    $durtion1 = $_POST['duration'];
    $durtion = $durtion1." days";
    

    $date=date_create($reservation_pickup);
date_add($date,date_interval_create_from_date_string($durtion));
$datedrop= date_format($date,"Ymd");
$reservation_date_drop =$datedrop;

    $carId= $car_id;
    $user_id = $_SESSION['user_id'];
    $eservation_status = "encours";
    $reservation_time = $_POST['time'];
    $location_price = $_POST['location_price'];
    $amount = $location_price*$durtion1;
    
    $description= $_POST['description'];


    $stmt4 = $conn->prepare("SELECT * FROM cars WHERE car_id=? LIMIT 1");
    $stmt4->bind_param("i",$car_id);
  
    $stmt4->execute();
    $cars4 = $stmt4->get_result();
    while($row = $cars->fetch_assoc()){
        $price_car =$row['car_price'];
    }
    $total = $price_car*$durtion;



    $stmt = $conn->prepare("INSERT INTO reservation (reservation_date, reservation_date_drop, reservation_pickup, car_id, user_id, reservation_status, heure_pickup, duration, description,amount)
                                                  VALUES(?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param('sssiissssi',$reservation_date,$reservation_date_drop,$reservation_pickup,$carId,$user_id,$eservation_status,$reservation_time,$durtion,$description,$amount);

    if($stmt->execute()){
    
        // get id reservation max
            $stmt5 = $conn->prepare("SELECT Max(reservation_id)AS idmax FROM reservation WHERE user_id=?");
            $stmt5->bind_param("i",$_SESSION['user_id']);
            $stmt5->execute();
            $idMax = $stmt5->get_result();
            while($row1 = $idMax->fetch_assoc()){
                $reservation_idmax =$row['idmax'];
            }

        //end to get id reservation code
        $id_transaction = rand();
      
        $_SESSION['car_date_drop']=$reservation_date_drop;
        $_SESSION['reservation_id']=$reservation_idmax;
        $_SESSION['car_id']=$car_id;
        $_SESSION['sub']=$amount;
        $_SESSION['trans']=$id_transaction;
//mail for customer

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';



    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gabeyes241@gmail.com';
    $mail->Password = 'uyms aryw rqbm lxro';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;


    $mail->setFrom('gabeyes241@gmail.com');

    $mail->addAddress($_SESSION['user_email']);

    $mail->isHTML(true);

    $mail->Subject = 'Reservation voiture';
    $mail->Body = '<p>Cher utilisateur <span style="color:green;">TAMICARS</span> <br> Merci pour la reservation de votre voiture : <span style="font-weight:blod;">'.$car_name
    .'</span> </p><br>
    <p>Si vous ne l\'avez pas effectuer cette action, ignorez et supprimer cet email. </p><br>
    <p> Equipe <span style="font-weight:blod;">Tomicars</span></p>';

    $mail->send();

// mail for tomicars

    $mail1 = new PHPMailer(true);

    $mail1->isSMTP();
    $mail1->Host = 'smtp.gmail.com';
    $mail1->SMTPAuth = true;
    $mail1->Username = 'gabeyes241@gmail.com';
    $mail1->Password = 'uyms aryw rqbm lxro';
    $mail1->SMTPSecure = 'ssl';
    $mail1->Port = 465;


    $mail1->setFrom($_SESSION['user_email'],$_SESSION['user_name']);

    $mail1->addAddress('gabeyes241@gmail.com');

    $mail1->isHTML(true);

    $mail1->Subject = 'Reservation voiture';
    $mail1->Body = '<p>Cher <span style="color:green;">TAMICARS</span> <br> Le client : <span style="font-weight:blod;">'.$_SESSION['user_name']
    .'</span>  a effectué une reservation du vehicule : '.$car_name.'</p><br>
    <p>Merci de bien vouloir le contacter afin de proceder a la confirmation. </p><br>
    <p> Equipe Technique <span style="font-weight:blod;">Tomicars</span></p>';

    $mail1->send();
            
        header('location:singpay.php');

    }else{
        header('location:products.php?product_failed=Product Error occured, try again');
    }
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
        <h1 class="display-3 text-uppercase text-white mb-3">Reservation</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="">Acceuil</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Reservation voiture</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <!-- Detail Start -->
    <div class="container-fluid pt-5">
        <div class="container pt-5 pb-3">
            <?php  while($row = $cars->fetch_assoc()){?>
            <h1 class="display-4 text-uppercase mb-5"><?php echo $row['car_name'] ;?></h1>
            <div class="row align-items-center pb-2">
                <div class="col-lg-6 mb-4">
                    <img class="img-fluid" src="assets/imgs/<?php  echo $row['car_image1']; ?>" alt="">
                </div>
                <div class="col-lg-6 mb-4">
                    <!-- <h4 class="mb-2"><?php  echo number_format($row['car_price']); ?> CFA/Jour</h4> -->
                    <div class="d-flex mb-3">
                        <h6 class="mr-2">Note:</h6>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <small class="fa fa-star text-success mr-1"></small>
                            <small class="fa fa-star text-success mr-1"></small>
                            <small class="fa fa-star text-success mr-1"></small>
                            <small class="fa fa-star text-success mr-1"></small>
                            <small class="fa fa-star-half-alt text-success mr-1"></small>
                            <small>(250)</small>
                        </div>
                    </div>
                    <p><?php echo $row['car_model']; ?></p>
                    <div class="d-flex pt-1">
                        <h6>Partager sur:</h6>
                        <div class="d-inline-flex">
                            <a class="px-2" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="px-2" href=""><i class="fab fa-twitter"></i></a>
                            <a class="px-2" href=""><i class="fab fa-linkedin-in"></i></a>
                            <a class="px-2" href=""><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-n3 mt-lg-0 pb-4">
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
            <?php  } ?>
        </div>
    </div>
    <!-- Detail End -->


    <!-- Car Booking Start -->
    <form action="" method="post">
    <div class="container-fluid pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="mb-4">Details Personnel</h2>
                    <div class="mb-5">
                        <div class="row">
                            <div class="col-6 form-group">
                                <input type="text" class="form-control p-4" value="<?php echo $_SESSION['user_name']; ?>" placeholder="Nom" required="required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <input type="email" class="form-control p-4"value="<?php echo $_SESSION['user_email']; ?>" placeholder=" Email" required="required">
                            </div>
                            <div class="col-6 form-group">
                                <input type="text" class="form-control p-4" placeholder="Tel" required="required">
                            </div>
                        </div>
                    </div>
                    <h2 class="mb-4">Réservation Detail</h2>
                    <div class="mb-5">
                        <div class="row">
                            <!-- <div class="col-6 form-group">
                                <select class="custom-select px-4" style="height: 50px;">
                                    <option selected>Pickup Location</option>
                                    <option value="1">Location 1</option>
                                    <option value="2">Location 2</option>
                                    <option value="3">Location 3</option>
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <select class="custom-select px-4" style="height: 50px;">
                                    <option selected>Drop Location</option>
                                    <option value="1">Location 1</option>
                                    <option value="2">Location 2</option>
                                    <option value="3">Location 3</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <div class="date" id="date2" data-target-input="nearest">
                                    <input type="text" class="form-control p-4 datetimepicker-input" name="reservation_date" placeholder="Date de récuperation"
                                        data-target="#date2" data-toggle="datetimepicker" required>
                                </div>
                            </div>
                            <div class="col-6 form-group">
                                <div class="time" id="time2" data-target-input="nearest">
                                    <input type="text" name="time"class="form-control p-4 datetimepicker-input" name="reservation_time" placeholder="Heure de récuperation"
                                        data-target="#time2" data-toggle="datetimepicker" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <select class="custom-select px-4" name="age" style="height: 50px;">
                                    <option selected>Selectioner votre age</option>
                                    <option value="1">18-23</option>
                                    <option value="2">24-30</option>
                                    <option value="3">30-60</option>
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <div class="number" id="nbrs">
                                    <input type="number" name="duration" class="form-control p-4 datetimepicker-input" placeholder="Nombre de jours"
                                        data-target="#nbrs" />
                                </div>
                            </div>
                        </div>
                        <div class="row">

                        
                        <div class="col-6 form-group">
                                <select class="custom-select px-4" name="location_price" style="height: 50px;">
                                    <option selected>Selectioner un lieu</option>

                                        <?php   
                                            $stmt4 = $conn->prepare("SELECT * from workspace left join location on workspace.location_id = location.location_id left join cars on workspace.car_id = cars.car_id  where cars.car_id=? ");
                                            $stmt4->bind_param("i",$car_id);
                                            $stmt4->execute();
                                            $products4 = $stmt4->get_result();
                                        ?>
                                     <?php  while($row1 = $products4->fetch_assoc()){?>
                                
                                            <option value="<?php  echo $row1['location_price']  ?>"><?php  echo $row1['location_name'] ?></option>
                            
                                    <?php  } ;?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control py-3 px-4"name="description" rows="3" placeholder="Description spéciale" required="required"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-secondary p-5 mb-5">
                        <h2 class="text-success mb-4">Payment</h2>
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="paypal">
                                <label class="custom-control-label" for="paypal">Paypal(SingPay)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="directcheck">
                                <label class="custom-control-label" for="directcheck">Mobile(SingPay)</label>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="banktransfer">
                                <label class="custom-control-label" for="banktransfer">Carte credit(SingPay)</label>
                            </div>
                        </div>
                        <button class="btn btn-block btn-success py-3" name="book_btn">Reserver maintenant</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    <!-- Car Booking End -->


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