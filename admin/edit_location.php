<?php  
session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['product_id'])){

    $location_id = $_GET['product_id'];
    $stmt2 = $conn->prepare("SELECT * from location where location_id=? ");
    $stmt2->bind_param('i',$location_id);
    $stmt2->execute();
    $products = $stmt2->get_result();
  

}else if(isset($_POST['edit_btn'])){
    $location_id = $_POST['location_id'];
    $location_name = $_POST['location_name'];
    $location_price = $_POST['location_price'];
    

    $stmt = $conn->prepare("UPDATE location SET  location_name= ?,location_price= ? where location_id = ?");
    $stmt->bind_param('sii',$location_name,$location_price,$location_id);


    if($stmt->execute()){
        header('location:table_location.php?edit_success_message=Location has been updated successfully');
    }else{
        header('location:table_location.php?edit_failure_message=Error occured, try again'); 
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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Modifier une localisation</h6>
                        </div>
                        <div class="card-body">
                            <form id="edit-form" action="edit_location.php"  method="post">
                                <p style="color:red;"><?php  if(isset($_GET['error'])){echo $_GET['error'];} ?></p>
                                <div class="form-group mt-2">
                                    <?php   foreach($products as $product)  { ?>
                                        <input type="hidden"  id="location_id" value="<?php  echo $product['location_id'] ; ?>" name="location_id" >
                                    <label>Nom</label>
                                    <input type="text" class="form-control" id="location_name" value="<?php  echo $product['location_name'] ; ?>" name="location_name" placeholder="Entrer le nom de la localisation" required>
                                </div>       
                                <div class="form-group mt-2">
                                    <label>prix</label>
                                    <input type="number" class="form-control" id="location_price" value="<?php  echo $product['location_price'] ; ?>" name="location_price" placeholder="prix" required>
                                </div>
                                <div class="form-group mt-2">
                                <input type="submit" value="Modifier" name="edit_btn" value="Edit" class="btn btn-primary" id="edit-btn">
                                </div>
                               
                                <?php  } ?>
                            </form>
                        </div>
                    </div>

                  

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