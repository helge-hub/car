<?php  
session_start();

include('../server/connection.php');


if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}



$stmt2 = $conn->prepare("SELECT * FROM `users` WHERE 1");
$stmt2->execute();
$products = $stmt2->get_result();


$stmt3 = $conn->prepare("SELECT EXTRACT(MONTH FROM reservation_date)AS mois,SUM(amount)AS ttc,CASE WHEN EXTRACT(MONTH FROM reservation_date)=1 THEN 'janivier' WHEN EXTRACT(MONTH FROM reservation_date)=2 THEN 'fevrier' WHEN EXTRACT(MONTH FROM reservation_date)=3 THEN 'mars' WHEN EXTRACT(MONTH FROM reservation_date)=4 THEN 'Avril' WHEN EXTRACT(MONTH FROM reservation_date)=5 THEN 'Mai' WHEN EXTRACT(MONTH FROM reservation_date)=6 THEN 'Juin' WHEN EXTRACT(MONTH FROM reservation_date)=7 THEN 'Juillet' WHEN EXTRACT(MONTH FROM reservation_date)=8 THEN 'Aout' WHEN EXTRACT(MONTH FROM reservation_date)=9 THEN 'Sptemebre' WHEN EXTRACT(MONTH FROM reservation_date)=10 THEN 'Octobre' WHEN EXTRACT(MONTH FROM reservation_date)=11 THEN 'Novembre' WHEN EXTRACT(MONTH FROM reservation_date)=12 THEN 'Décembre' END AS nom FROM reservation GROUP BY reservation_date;");


$stmt3->execute();

$total_ca = $stmt3->get_result();


while($row=$total_ca->fetch_assoc())
{
    $mois[]=$row["nom"];     
    $ttcs[]=$row["ttc"];     
}




$stmt41 = $conn->prepare("SELECT COUNT(reservation_id)as co,mode FROM `reservation`  GROUP BY mode");


$stmt41->execute();

$total_stat1 = $stmt41->get_result();


while($row41=$total_stat1->fetch_assoc())
{
    $statut41[]=$row41["mode"];     
    $nbrs41[]=$row41["co"];     
}

// order paid
$order_status = "encours";

$stmt = $conn->prepare("SELECT count(reservation_id)as total FROM `reservation` WHERE reservation_status= ?");

$stmt->bind_param("s",$order_status);

$stmt->execute();

$total_order_paid = $stmt->get_result();

//order not paid
$order_status1 = "terminer";

$stmt1 = $conn->prepare("SELECT count(reservation_id)as total FROM `reservation` WHERE reservation_status= ?");

$stmt1->bind_param("s",$order_status1);

$stmt1->execute();

$total_order_not_paid = $stmt1->get_result();


$stmt2 = $conn->prepare("SELECT count(user_id)as total FROM `users`");


$stmt2->execute();

$total_customers = $stmt2->get_result();


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ExpressDxB Admin 1 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
       <?php include('sidebar.php') ; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
               <?php include('topbar.php') ; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                <?php if(isset($_GET['order_updated'])) { ?>
                            <p class="text-center" style="color:green"><?php   echo $_GET['order_updated'] ;?></p>
                            <?php   } ?>
                           <?php if(isset($_GET['order_failed'])) { ?>
                            <p class="text-center" style="color:red"><?php   echo $_GET['order_failed'] ;?></p>
                            <?php   } ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Réservation encours</div>
                                                <?php  while($row = $total_order_paid->fetch_assoc()){?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $row['total'];  ?></div>
                                            <?php  } ?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Réservation terminer</div>
                                                <?php  while($row = $total_order_not_paid->fetch_assoc()){?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $row['total'];  ?></div>
                                            <?php  } ?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Clients</div>
                                                <?php  while($row = $total_customers->fetch_assoc()){?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $row['total']; ?></div>
                                            <?php  } ?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">chiffre d'affaire</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Mode de paiement</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myChart1"></canvas>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Liste des clients</h6>
                                </div>
                                <div class="card-body">
                                     <div class="table-responsive">

                                        <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>icon</th>
                                        <th>Nom</th>						
                                        <th>email</th>
                                        <th>téléphone</th>
                                        <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php   foreach($products as $product) {  ?>
                                        <tr>
                                        <td><?php  echo $product['user_id']; ?></td>
                                        <td><a href="#"><img class="rounded-circle" src="../assets/imgs/user.png" class="avatar" alt="Avatar" style="width:50px;height:50px"></a></td>
                                        <td><?php echo $product['user_name'];  ?></td>                        
                                        <td><?php echo $product['user_email'];  ?></td>                        
                                        <td><?php echo $product['mobile']; ?></td>
                                        <td>
                                        <a data-id='<?php echo $product['user_id']; ?>' class="userinfo"><i class="fa fa-eye"></i></a>
                                        </td>
                                        </tr>
                                        <?php  } ?>
                                        </tbody>
                                        </table>
                                     </div>
                                </div>
                            </div>

                            <!-- Color System -->
                            <!-- <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            Primary
                                            <div class="text-white-50 small">#4e73df</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-success text-white shadow">
                                        <div class="card-body">
                                            Success
                                            <div class="text-white-50 small">#1cc88a</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            Info
                                            <div class="text-white-50 small">#36b9cc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            Warning
                                            <div class="text-white-50 small">#f6c23e</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-danger text-white shadow">
                                        <div class="card-body">
                                            Danger
                                            <div class="text-white-50 small">#e74a3b</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-secondary text-white shadow">
                                        <div class="card-body">
                                            Secondary
                                            <div class="text-white-50 small">#858796</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-light text-black shadow">
                                        <div class="card-body">
                                            Light
                                            <div class="text-black-50 small">#f8f9fc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-dark text-white shadow">
                                        <div class="card-body">
                                            Dark
                                            <div class="text-white-50 small">#5a5c69</div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Location par type</h6>
                                </div>
                                <?php  
                                         $stmt6 = $conn->prepare("SELECT * FROM `category` WHERE 1");
                                         $stmt6->execute();
                                         $category = $stmt6->get_result();   
                                        ?>
                               
                                <div class="card-body">
                                <?php  while($row1 = $category->fetch_assoc()){?>
                                     <div class="row">
                                       
                                         <!-- amount total indicidual -->
                                            
                                         <?php 
                                         $category_name = $row1['category_name'];

                                         $stmt7 = $conn->prepare("SELECT SUM(amount)as amount FROM `reservation` left join cars on reservation.car_id = cars.car_id WHERE cars.car_type=?");
                                         $stmt7->bind_param("s",$category_name);
                                         $stmt7->execute();
                                         $get_amount_ind = $stmt7->get_result(); 

                                         while($row2 = $get_amount_ind->fetch_assoc()){
                                            $amount_ind = $row2['amount'];
                                         }

                                         
                                         $stmt8 = $conn->prepare("SELECT SUM(amount)as amount FROM `reservation` WHERE 1");
                                         $stmt8->execute();
                                         $get_amount_total = $stmt8->get_result(); 

                                         while($row3 = $get_amount_total->fetch_assoc()){
                                            $amount_total = $row3['amount'];
                                         }

                                         $amount_type = ($amount_ind/$amount_total)*100;
                                        ?>

                                        


                                         
                                   
                                            
                                            <div class="col">
                                                <img src="../assets/imgs/<?php echo $row1['category_image'];?>">---
                                                
                                                <?php echo $row1['category_name']; ?>----
                                            
                                           

                                            
                                                <?php 
                                                $formatted_percentage = sprintf("%.2f%%", $amount_type);
                                                echo $formatted_percentage;
                                                 ?>
                                             </div>
                                            
                                            
                                           
                                    </div>
                                    <?php  } ?> 
                                </div>
                            </div>

                            <!-- Approach -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Lieu</h6>
                                </div>
                                <div class="card-body">
                                <div class="chart-area">
                                <?php  
                                         $stmt9 = $conn->prepare("SELECT * FROM `location` WHERE 1");
                                         $stmt9->execute();
                                         $location = $stmt9->get_result();   
                                        ?>
                               
                                <div class="card-body">
                                <?php  while($row9 = $location->fetch_assoc()){?>
                                     <div class="row">
                                       
                                         <!-- amount total indicidual -->
                                            
                                         <?php 
                                         $location_name = $row9['location_name'];

                                         $stmt10 = $conn->prepare("SELECT COUNT(reservation_id)as amount FROM `reservation` left join cars on reservation.car_id = cars.car_id left join workspace on reservation.car_id=workspace.car_id left join location on workspace.location_id=location.location_id WHERE location.location_name=?");
                                         $stmt10->bind_param("s",$location_name);
                                         $stmt10->execute();
                                         $get_amount_ind10 = $stmt10->get_result(); 

                                         while($row10= $get_amount_ind10->fetch_assoc()){
                                            $nbrs = $row10['amount'];
                                         }

                                         
                                        ?>

                                        


                                         
                                   
                                            
                                            <div class="col">
                                                <img src="../assets/imgs/map.png">---
                                                
                                                <?php echo $row9['location_name']; ?>----
                                            
                                           

                                            
                                                <?php 
                                                
                                                echo $nbrs;
                                                 ?>
                                             </div>
                                            
                                            
                                           
                                    </div>
                                    <?php  } ?> 
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <canvas id="bar-chart-custom-tooltip"></canvas>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Gabeyes Website 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php?logout=1">Logout</a>
                </div>
            </div>
        </div>
    </div>


        <!-- start display info customers modal -->


        <form action="" method="post">
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">PIECE D'IDENTITE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
       
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
                        url: 'ajaxfile1.php',
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


        <!-- end display -->



    <script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($mois) ?>,
      datasets: [{
        label: 'chiffre affaire',
        data: <?php echo json_encode($ttcs) ?>,
        borderWidth: 2
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

<script>
  const ctx1 = document.getElementById('myChart1');

  new Chart(ctx1, {
    type: 'doughnut',
    data: {
      labels: <?php echo json_encode($statut41) ?>,
      datasets: [{
        label: 'mode de paiement',
        data: <?php echo json_encode($nbrs41) ?>,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<script>
 
 const ctx2 = document.getElementById('myChart2');

  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($mois) ?>,
      datasets: [{
        label: 'lieux',
        data: <?php echo json_encode($ttcs) ?>,
        borderWidth: 2
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="vendor/chart.js/Chart.min.js"></script>

    Page level custom scripts
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script> -->

</body>

</html>