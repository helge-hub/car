<?php  
session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}



   
if(isset($_GET['page_no'])  && $_GET['page_no'] != ""){
    $page_no = $_GET['page_no'];
   }else{
    $page_no = 1;
   }
  
  // return number of products 
   $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM location");
  
    $stmt1->execute();
  
    $stmt1->bind_result($total_records);
  
    $stmt1->store_result();
  
    $stmt1->fetch();
  
    //3. product per page
    $total_records_per_page = 8;
  
    $offset =($page_no-1) * $total_records_per_page;
  
    $previous_page = $page_no -1;
    $next_page = $page_no +1;
  
    $adjcents = "2";
  
    $total_no_of_pages = ceil($total_records/$total_records_per_page);
  
  //4. gets all products
  $stmt2 = $conn->prepare("SELECT * from location limit $offset,$total_records_per_page");
  $stmt2->execute();
  $products = $stmt2->get_result();


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Liste des location</h6>
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
                        <div class="card-body">
                            <div class="table-responsive">

                    <table class="table table-striped table-sm table-bordered" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   foreach($products as $product) {  ?>
                            <tr>
                            <td><?php echo $product['location_id'] ; ?></td>
                            <td><?php echo $product['location_name'] ; ?></td>
                            <td><?php echo $product['location_price'] ; ?></td>
                            <td><a class="btn btn-primary" href="edit_location.php?product_id=<?php echo $product['location_id'] ; ?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a class="btn btn-danger" href="delete_location.php?product_id=<?php echo $product['location_id'] ; ?>"><i class="fa-solid fa-trash"></i></a></td>
                            </tr>
                            <?php    } ?>
                        </tbody>
                    </table>
                    </div>
                    </div>
                    </div>

                  

                <!-- start pagination -->


                <nav aria-label="Page navigation example">
                <ul class="pagination mt-5 mx-auto">
                    <li class="page-item <?php if($page_no<=1){echo 'disabled'; }  ?>">
                      <a href="<?php if($page_no<=1){echo '#';}else{echo "?page_no=".($page_no-1);} ?>" class="page-link">Previous</a>
                    </li>
                    <li class="page-item"><a href="?page_no=1" class="page-link">1</a></li>
                    <li class="page-item"><a href="?page_no=2" class="page-link">2</a></li>
                    
                    <?php if($page_no >=3){ ?>
                    <li class="page-item"><a href="#" class="page-link">...</a></li>
                    <li class="page-item"><a href="<?php echo  "?page_no=".$page_no ;?>" class="page-link"><?php echo $page_no;  ?></a></li>
                     
                    <?php  } ?>

                    <li class="page-item <?php if($page_no>= $total_no_of_pages){echo 'disabled'; } ?>">
                      <a href="<?php if($page_no>=$total_no_of_pages){ echo '#';}else{echo "?page_no=".($page_no+1);}  ?>" class="page-link">Next</a>
                    </li>
                </ul>
            </nav>

                <!-- end pagination -->
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