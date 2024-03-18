<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$_SESSION['nbrs']=0;
include('server/connection.php');
if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
  }

  if(isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
      unset($_SESSION['logged_in']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
  
      header('location: login.php');
      exit;
    }
  }

  
  
  
  if(isset($_POST['search'])){
  
      
       
    
      $category = $_POST['category'];
       

      $stmt2 = $conn->prepare("SELECT COUNT(*) from cars  where car_type=?");
      $stmt2->bind_param("s",$category);
      $stmt2->execute();
      $stmt2->bind_result($num_rows);
      $stmt2->store_result();
      $stmt2->fetch();

      if($num_rows != 0){
       $_SESSION['nbrs']=1;
       }else{
        $_SESSION['nbrs']=2;
       }
  
  
    }
  if(isset($_POST['all'])){
  
      $category = $_POST['category'];
       
      $stmt2 = $conn->prepare("SELECT COUNT(*) from cars  where 1");
      $stmt2->execute();
      $stmt2->bind_result($num_rows);
      $stmt2->store_result();
      $stmt2->fetch();
      $_SESSION['nbrs']=3;
  
  
    }
  if(isset($_POST['btn_all'])){
  
     
      $stmt2 = $conn->prepare("SELECT COUNT(*) from cars  where 1");
      $stmt2->execute();
      $stmt2->bind_result($num_rows);
      $stmt2->store_result();
      $stmt2->fetch();
      $_SESSION['nbrs']=3;
  
  
    }

  
  
$user_id = $_SESSION['user_id'];

$stmt2 = $conn->prepare("SELECT * FROM `reservation` left JOIN users ON reservation.user_id=users.user_id LEFT JOIN cars ON reservation.car_id=cars.car_id WHERE reservation.user_id=? and reservation.reservation_status='encours'");
$stmt2->bind_param('i',$user_id);
$stmt2->execute();
$products = $stmt2->get_result();



$stmt3 = $conn->prepare("SELECT * FROM `reservation` left JOIN users ON reservation.user_id=users.user_id LEFT JOIN cars ON reservation.car_id=cars.car_id WHERE reservation.user_id=? and reservation.reservation_status='terminer'");
$stmt3->bind_param('i',$user_id);
$stmt3->execute();
$products1 = $stmt3->get_result();



if(isset($_POST['book_btn'])){


  $reservation_date = date("Ymd");;//date du jour
  $car_date_o= $_POST['car_date_o'];
  $reservation_pickup1 = $_POST['reservation_date'];
  $reservation_pickup= date("Ymd", strtotime($reservation_pickup1));
  if($reservation_pickup<$car_date_o){
    $date_reservation_final =$car_date_o;
  }else{
    $date_reservation_final=$reservation_pickup;
  }
  $durtion1 = $_POST['duration'];
  $durtion = $durtion1." days";
  

  $date=date_create($date_reservation_final);
date_add($date,date_interval_create_from_date_string($durtion));
$datedrop= date_format($date,"Ymd");
$reservation_date_drop =$datedrop;

  $carId= $_POST['car_id'];
  $user_id = $_SESSION['user_id'];
  $eservation_status = "encours";
  $reservation_time = $_POST['time'];
  $location_price = $_POST['location_price'];
  $description= $_POST['description'];
  $amount =$location_price*$durtion1;
  $image1 = $_FILES['image1']['tmp_name'];
  $id_permis = rand();

 // $file_name = $_FILES['image1']['name'];

  $image_name1 = $id_permis."1.jpg";
 


  move_uploaded_file($image1,"assets/imgs/".$image_name1);


  $stmt4 = $conn->prepare("SELECT * FROM cars WHERE car_id=? LIMIT 1");
  $stmt4->bind_param("i",$carId);

  $stmt4->execute();
  $cars4 = $stmt4->get_result();
  while($row = $cars4->fetch_assoc()){
      $price_car =$row['car_price'];
      $car_name =$row['car_name'];
  }
  $total = $price_car*$durtion1;



  $stmt = $conn->prepare("INSERT INTO reservation (reservation_date, reservation_date_drop, reservation_pickup, car_id, user_id, reservation_status, heure_pickup, duration, description,amount,permis)
                                                VALUES(?,?,?,?,?,?,?,?,?,?,?)");

  $stmt->bind_param('sssiissssis',$reservation_date,$reservation_date_drop,$date_reservation_final,$carId,$user_id,$eservation_status,$reservation_time,$durtion1,$description,$amount,$image_name1);

  if($stmt->execute()){

    // get id reservation max
    $stmt5 = $conn->prepare("SELECT Max(reservation_id)AS idmax FROM reservation WHERE user_id=?;");
    $stmt5->bind_param("i",$_SESSION['user_id']);
    $stmt5->execute();
    $idMax = $stmt5->get_result();
    while($row1 = $idMax->fetch_assoc()){
        $reservation_idmax =$row1['idmax'];
       
    }

//end to get id reservation code

      $id_transaction = rand();
    
      $_SESSION['car_date_drop']=$reservation_date_drop;
      $_SESSION['reservation_id']=$reservation_idmax;
      $_SESSION['car_id']=$carId;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
    <div class="container-fluid bg-white pt-3 px-lg-5">
                    <form action="account.php" method="post">
                <?php
                    $stmt3 = $conn->prepare("SELECT * from category ORDER by category_name");
                            $stmt3->execute();
                            $category_all = $stmt3->get_result();
                ?>
        <div class="row mx-n2">
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <select class="custom-select px-4 mb-3" style="height: 50px;">
                    <option selected>Localisation Récuperation</option>
                    <option value="1">Libreville</option>
                    <option value="2">Akanda</option>
                    <option value="3">Owendo</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <select class="custom-select px-4 mb-3" style="height: 50px;">
                    <option selected>Localisation Dépôt</option>
                    <option value="1">Libreville</option>
                    <option value="2">Akanda</option>
                    <option value="3">Owendo</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <div class="date mb-3" id="date" data-target-input="nearest">
                    <input type="text" class="form-control p-4 datetimepicker-input" placeholder="Date Récuperation"
                        data-target="#date" data-toggle="datetimepicker" />
                </div>
            </div>
            <!-- <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <div class="time mb-3" id="time" data-target-input="nearest">
                    <input type="text" class="form-control p-4 datetimepicker-input" placeholder="Heure Récuperation"
                        data-target="#time" data-toggle="datetimepicker" />
                </div>
            </div> -->
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <select class="custom-select px-4 mb-3" style="height: 50px;" name="category">
                    <!-- <option selected>Select A Car</option> -->
                    <?php foreach($category_all as $row) {   ?>
                    <option  value="<?php echo $row['category_name']; ?>"><?php  echo $row['category_name']; ?></option>
                    <?php  } ; ?>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <button class="btn btn-success btn-block mb-3" name="search" type="submit" style="height: 50px;">Recherche</button>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <button class="btn btn-secondary btn-block mb-3" name="all" type="submit" style="height: 50px;">Tous</button>
            </div>
        </div>
    </form>
</div>
    <!-- Search End -->
    <section style="background-color: #eee;">
<!-- display search result -->





<?php  

if($_SESSION['nbrs']==1){ ?>


 <?php $stmt2 = $conn->prepare("SELECT * from cars  where car_type=?");
    $stmt2->bind_param("s",$category);
    $stmt2->execute();
    $products6 = $stmt2->get_result();

    ?>

     <div>

       
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="owl-carousel team-carousel position-relative" style="padding: 0 30px;">
            <?php  while ($row = $products6->fetch_assoc()){ ?>
              <?php   $car_id8 = $row['car_id'] ;?>

              <?php    

                    $stmt4 = $conn->prepare("SELECT MAX(location.location_price)as maxPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
                    $stmt4->bind_param("i",$car_id8);
                    $stmt4->execute();
                    $car_id_max = $stmt4->get_result();
                      while ($row1 = $car_id_max->fetch_assoc()){
                        $maxPrice = $row1['maxPrice'];
                      }

                    $stmt5 = $conn->prepare("SELECT MIN(location.location_price)as minPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
                    $stmt5->bind_param("i",$car_id8);
                    $stmt5->execute();
                    $car_id_min = $stmt5->get_result();
                      while ($row2 = $car_id_min->fetch_assoc()){
                        $minPrice = $row2['minPrice'];
                      }


                    

                    ?>


            <div class="team-item">
                    <img class="img-fluid w-100" src="assets/imgs/<?php echo $row["car_image1"]; ?>" alt="">
                    <div class="position-relative py-4">
                        <h4 class="text-uppercase"><?php  echo $row["car_name"]; ?></h4>
                        <p class="m-0"><?php echo $minPrice; ?>- <?php echo $maxPrice; ?> CFA  /jour</p>
                        <div class="team-social position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <button data-id='<?php echo $row['car_id']; ?>' class="userinfo btn btn-success"><i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </div>



                <?php  } ?>
                
               
            </div>
        </div>
    </div>
    
  
  </div>
    

       


<?php }else if($_SESSION['nbrs']==2){ ?>

  <div class="container-fluid py-5">
        <div class="container py-5">
            <img class="img-fluid d-block mx-auto" src="assets/imgs/planet.png">
        </div>
        <div class="text-center">Aucune voiture trouvée !!!</div>
    </div>

  
<?php }else if($_SESSION['nbrs']==3){?>

  <?php $stmt2 = $conn->prepare("SELECT * from cars  where 1");
    $stmt2->execute();
    $products6 = $stmt2->get_result();

    ?>

     <div>

       
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="owl-carousel team-carousel position-relative" style="padding: 0 30px;">
            <?php  while ($row = $products6->fetch_assoc()){ ?>

              <?php   $car_id8 = $row['car_id'] ;?>

              <?php    

                    $stmt4 = $conn->prepare("SELECT MAX(location.location_price)as maxPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
                    $stmt4->bind_param("i",$car_id8);
                    $stmt4->execute();
                    $car_id_max = $stmt4->get_result();
                      while ($row1 = $car_id_max->fetch_assoc()){
                        $maxPrice = $row1['maxPrice'];
                      }

                    $stmt5 = $conn->prepare("SELECT MIN(location.location_price)as minPrice FROM `workspace` left join location ON workspace.location_id=location.location_id LEFT JOIN cars ON workspace.car_id=cars.car_id where cars.car_id=?");
                    $stmt5->bind_param("i",$car_id8);
                    $stmt5->execute();
                    $car_id_min = $stmt5->get_result();
                      while ($row2 = $car_id_min->fetch_assoc()){
                        $minPrice = $row2['minPrice'];
                      }


                    

                    ?>

            <div class="team-item">
                    <img class="img-fluid w-100" src="assets/imgs/<?php echo $row["car_image1"]; ?>" alt="">
                    <div class="position-relative py-4">
                        <h4 class="text-uppercase"><?php  echo $row["car_name"]; ?></h4>
                        <p class="m-0"><?php echo $minPrice; ?>- <?php echo $maxPrice; ?> CFA  /jour</p>
                        <div class="team-social position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <button data-id='<?php echo $row['car_id']; ?>' class="userinfo btn btn-success"><i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </div>



                <?php  } ?>
                
               
            </div>
        </div>
    </div>
    
  
  </div>

<?php   } ?>
   
  <!-- end to display search result -->

  <div class="container py-5">
    <div class="row">
      <div class="col">
        <!-- <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Profile</li>
          </ol>
        </nav> -->
      </div>
    </div>

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="assets/imgs/user.png" alt="avatar"
              class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3"><?php echo $_SESSION['user_name']; ?></h5>
            <p class="text-muted mb-1"><?php echo $_SESSION['user_email'];  ?></p>
            <div class="d-flex justify-content-center mb-2">
              <a href="account.php?logout=1"  type="button" class="btn btn-success">Déconnecer</a>
            </div>
          </div>
        </div>
        <div class="card mb-4 mb-lg-0">
          <div class="card-body p-0">
            <ul class="list-group list-group-flush rounded-3">
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fas fa-comment fa-lg text-warning"></i>
                <a href="feedback.php" class="mb-0">Laisser votre avis</a>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-github fa-lg" style="color: #333333;"></i>
                <p class="mb-0">mdbootstrap</p>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-twitter fa-lg" style="color: #55acee;"></i>
                <p class="mb-0">@mdbootstrap</p>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-instagram fa-lg" style="color: #ac2bac;"></i>
                <p class="mb-0">mdbootstrap</p>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-facebook-f fa-lg" style="color: #3b5998;"></i>
                <p class="mb-0">mdbootstrap</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nom complet</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php  echo $_SESSION['user_name']; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $_SESSION['user_email']; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Telephone</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php  echo $_SESSION['mobile']; ?></p>
              </div>
            </div>
            <hr>
           
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-4 mb-md-0">
              <div class="card-body">
                <p class="mb-4"><span class="text-success font-italic me-1">Voitures</span> en cours
                </p>
                <div class="table-responsive">

<table class="table table-striped table-hover">
<thead>
<tr>
<th>#</th>
<th>Voiture</th>
<th>Type</th>						
<th>Model</th>
<th>prix</th>
<th>durée</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php   foreach($products as $product) {  ?>
<tr>
<td><?php  echo $product['car_id']; ?></td>
<td><a href="#"><img class="rounded-circle" src="assets/imgs/<?php echo $product['car_image1']; ?>" class="avatar" alt="Avatar" style="width:50px;height:50px"></a></td>
<td><?php echo $product['car_name'];  ?></td>                        
<td><?php echo $product['trans']; ?></td>
<td><?php echo $product['amount']; ?></td>
<td><?php echo $product['duration']; ?> jour(s)</td>
<td>
<a href="paid.php?id=<?php echo $product['car_id']; ?>" class="modifier" title="proceder au paiement" data-toggle="tooltip"><i class="fa fa-money-bill">&#xE8B8;</i></a>
<a href="paid.php?id=<?php echo $product['car_id']; ?>" class="modifier" title="proceder au paiement" data-toggle="tooltip"><i class="fa fa-credit-card">&#xE8B8;</i></a>
</td>
</tr>
<?php  } ?>

</tbody>
</table>
</div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-4 mb-md-0">
              <div class="card-body">
                <p class="mb-4"><span class="text-success font-italic me-1">Vos dernières</span> reservation 
                </p>
                <div class="table-responsive">

                        <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                        <th>#</th>
                        <th>Voiture</th>
                        <th>Type</th>						
                        <th>Model</th>
                        <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php   foreach($products1 as $product1) {  ?>
                        <tr>
                        <td><?php  echo $product1['car_id']; ?></td>
                        <td><a href="#"><img class="rounded-circle" src="assets/imgs/<?php echo $product1['car_image1']; ?>" class="avatar" alt="Avatar" style="width:50px;height:50px"></a></td>
                        <td><?php echo $product1['car_name'];  ?></td>                        
                        <td><?php echo $product1['trans']; ?></td>
                        </tr>
                        <?php  } ?>

                        </tbody>
                        </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Modal -->
<form action="" method="post">
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">DETAIL DE LA VOITURE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="submit" name="book_btn" class="btn btn-success">RESERVER MAINTENANT</button>
      </div>
    </div>
  </div>
</div>
</form>


        <script type='text/javascript'>
            $(document).ready(function(){
                $('.userinfo').click(function(){
                    var userid = $(this).data('id');
                    $.ajax({
                        url: 'ajaxfile.php',
                        type: 'post',
                        data: {userid: userid},
                        success: function(response){ 
                            $('.modal-body').html(response); 
                            $('#exampleModalCenter').modal('show'); 
                        }
                    });
                });
            });
            </script>
    <!-- Footer Start -->
   <?php include('footer.php') ; ?>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-success btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

   
    <!-- JavaScript Libraries -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
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