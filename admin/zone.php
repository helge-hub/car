<?php  
session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['product_id'])){

    $car_id = $_GET['product_id'];
    $stmt2 = $conn->prepare("SELECT * from cars where car_id=? ");
    $stmt2->bind_param('i',$car_id);
    $stmt2->execute();
    $products = $stmt2->get_result();

    $stmt5 = $conn->prepare("SELECT * from workspace left join location on workspace.location_id=location.location_id left join cars on workspace.car_id=cars.car_id where cars.car_id=? ");
    $stmt5->bind_param('i',$car_id);
    $stmt5->execute();
    $products6 = $stmt5->get_result();


  
    $stmt3 = $conn->prepare("SELECT * from location where 1");
    $stmt3->execute();
    $products1 = $stmt3->get_result();
  

}else if(isset($_POST['edit_btn'])){
    $car_id = $_POST['car_id'];
    $location_id = $_POST['location_id'];
    

    $stmt = $conn->prepare("INSERT INTO workspace (location_id, car_id)
                                                  VALUES(?,?)");
    $stmt->bind_param('ii',$location_id,$car_id);


    if($stmt->execute()){
        header('location:zone.php?product_id='.$car_id.'&edit_success_message=Location has been updated successfully');
    }else{
        header('location:zone.php?product_id='.$car_id.'&edit_failure_message=Error occured, try again'); 
    }


  


}else{
   
    header('location:table_location.php');
    exit;
   
}
  

   



?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

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

                    <!--table -->
                    <form id="edit-form" action="zone.php"  method="post">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Modifier une localisation</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                
                                <p style="color:red;"><?php  if(isset($_GET['error'])){echo $_GET['error'];} ?></p>


                                <div class="col shadow m-2">
                                
                                    <div class="form-group mt-5">
                                        <label>Lieux</label>
                                        <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="location_id">
                                            
                                            <?php   foreach($products1 as $product1)  { ?>
                                            <option  value="<?php echo $product1['location_id'];  ?>" ><?php   echo "Zone: ".$product1['location_name']." : ".$product1['location_price']."CFA / Jour" ; ?></option>
                                            
                                            <?php  } ?>   
                                        </select>
                                    </div>
                                    <div>

                                    <input type="submit"  name="edit_btn" value="Ajouter" class="btn btn-primary" id="edit-btn">

                                    </div>
                                </div>



                                <div class="col shadow m-2">
                                    <?php   foreach($products as $product)  { ?>
                                        <input type="hidden" value="<?php echo $product['car_id'];  ?>" name="car_id">
                                    <div>
                                        <img  src="../assets/imgs/<?php  echo $product['car_image1'] ;?>" alt="" style="max-width: 100%;height: auto;">
                                    </div>
                                    <div><?php echo $product['car_name']; ?></div>
                                    <hr>
                                    <div><?php echo $product['car_model']; ?></div>
                                    <?php  } ?>   
                                </div>

                               

                                
                               
                             </div>  
                        </div>
                            <div class="col shadow m-2">
                            <div>
                            <?php if(isset($_GET['edit_success_message'])) { ?>
                            <p class="text-center" style="color:green"><?php   echo $_GET['edit_success_message'] ;?></p>
                            <?php   } ?>
                           <?php if(isset($_GET['edit_failure_message'])) { ?>
                            <p class="text-center" style="color:red"><?php   echo $_GET['edit_failure_message'] ;?></p>
                            <?php   } ?>

                            <?php if(isset($_GET['deleted_failure'])) { ?>
                            <p class="text-center" style="color:red"><?php   echo $_GET['deleted_failure'] ;?></p>
                            <?php   } ?>

                            <?php if(isset($_GET['deleted_successfully'])) { ?>
                            <p class="text-center" style="color:green"><?php   echo $_GET['deleted_successfully'] ;?></p>
                            <?php   } ?>

                            <?php if(isset($_GET['product_created'])) { ?>
                            <p class="text-center" style="color:green"><?php   echo $_GET['product_created'] ;?></p>
                            <?php   } ?>

                            <?php if(isset($_GET['product_failed'])) { ?>
                            <p class="text-center" style="color:red"><?php   echo $_GET['product_failed'] ;?></p>
                            <?php   } ?>

                            <?php if(isset($_GET['images_updated'])) { ?>
                            <p class="text-center" style="color:green"><?php   echo $_GET['images_updated'] ;?></p>
                            <?php   } ?>

                            <?php if(isset($_GET['images_failed'])) { ?>
                            <p class="text-center" style="color:red"><?php   echo $_GET['images_failed'] ;?></p>
                            <?php   } ?>
                            </div>
                            <div class="table-responsive mt-2">

                                <table class="table table-striped table-sm table-bordered mt-3" width="100%" cellspacing="0">
                                    <thead class="table-dark">
                                        <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nom du lieu</th>
                                        <th scope="col">prix</th>
                                        <th scope="col">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php   foreach($products6 as $product) {  ?>
                                        <tr>
                                        <td><?php echo $product['workspace_id'] ; ?></td>
                                        <td><?php echo $product['location_name'] ; ?></td>
                                        <td><?php echo $product['location_price'] ; ?></td>
                                        <td><a class="btn btn-danger" href="delete_zone.php?product_id=<?php echo $product['workspace_id'] ; ?>"><i class="fas fa-fw fa-trash"></i></a></td>
                                        </tr>
                                        <?php    } ?>
                                    </tbody>
                                </table>
                            </div>

                            </div>
                        </div>
                    </form>

                  

                <!-- start pagination -->


                
                <!-- end pagination -->
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>