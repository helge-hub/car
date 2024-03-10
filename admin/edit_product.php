<?php  
session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}
$stmt = $conn->prepare("SELECT * FROM category");

$stmt->execute();

$category = $stmt->get_result();

$stmt1 = $conn->prepare("SELECT * FROM partener");

$stmt1->execute();

$partener = $stmt1->get_result();

if(isset($_GET['product_id'])){

    $product_id = $_GET['product_id'];
    $stmt2 = $conn->prepare("SELECT * from cars where car_id=? ");
    $stmt2->bind_param('i',$product_id);
    $stmt2->execute();
    $products = $stmt2->get_result();
  

}else if(isset($_POST['edit_btn'])){
    $car_id = $_POST['car_id'];
    $car_name = $_POST['car_name'];
    $car_model = $_POST['description'];
    $car_km = $_POST['car_km'];
    $car_gps = $_POST['car_gps'];
    $car_price = $_POST['car_price'];
    $car_type = $_POST['category'];
    $an = $_POST['an'];
    $trans = $_POST['trans'];
    $car_sit = $_POST['car_sit'];
    $car_autoradio = $_POST['car_autoradio'];
    $partener_id = $_POST['partener_id'];
    

    $stmt = $conn->prepare("UPDATE cars SET  car_name= ?,car_model= ?,car_km= ?,car_gps= ?,car_price= ?,car_type= ?,an= ?,trans= ?,car_autoradio=?,car_sit=?,partener_id=? where car_id = ?");
    $stmt->bind_param('sssssssssiii',$car_name,$car_model,$car_km,$car_gps,$car_price,$car_type,$an,$trans,$car_autoradio,$car_sit,$partener_id,$car_id);


    if($stmt->execute()){
        header('location:products.php?edit_success_message=Product has been updated successfully');
    }else{
        header('location:products.php?edit_failure_message=Error occured, try again'); 
    }


  


}else{
   
    header('location:products.php');
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
                            <h6 class="m-0 font-weight-bold text-primary">Modifier une voiture</h6>
                        </div>
                        <div class="card-body">
                            <form id="edit-form" action="edit_product.php"  method="post">
                                <p style="color:red;"><?php  if(isset($_GET['error'])){echo $_GET['error'];} ?></p>
                                <div class="form-group mt-2">
                                    <?php   foreach($products as $product)  { ?>
                                        <input type="hidden"  id="car_id" value="<?php  echo $product['car_id'] ; ?>" name="car_id" >
                                    <label>Nom</label>
                                    <input type="text" class="form-control" id="car_name" value="<?php  echo $product['car_name'] ; ?>" name="car_name" placeholder="Entrer le nom de la voiture" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Description</label>
                                    <textarea  class="form-control" id="car_model"  name="description" placeholder="Description" required><?php  echo $product['car_model'] ; ?></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Kilométrage</label>
                                    <input type="text" class="form-control" id="car_km" value="<?php  echo $product['car_km'] ; ?>" name="car_km" placeholder="kilometrage" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label>nombre de place</label>
                                    <input type="number" class="form-control" id="car_sit" value="<?php  echo $product['car_sit'] ; ?>" name="car_sit" placeholder="enter le nombre de place" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label>GPS</label>
                                    <input type="text" class="form-control" id="car_gps" value="<?php  echo $product['car_gps'] ; ?>" name="car_gps" placeholder="oui/non" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Prix / jour</label>
                                    <input type="text" class="form-control" id="car_price" value="<?php  echo $product['car_price'] ; ?>" name="car_price" placeholder="prix" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Categorie</label>
                                    <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="category">
                                        <?php  foreach($category as $category){  ?>
                                        <option  value="<?php echo $category['category_name']; ?>" <?php  if($category['category_name'] == $product['car_type']){echo "selected";} ?>><?php  echo $category['category_name'] ;?></option>
                                        <?php  } ?>
                                     </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Partenaire</label>
                                    <select class="form-select form-select-lg mb-3" aria-label="Large select partener" name="partener_id">
                                        <?php  foreach($partener as $partener){  ?>
                                            <?php $partener_name=$partener['partener_name'] ;?>
                                        <option  value="<?php echo $partener['partener_id']; ?>" <?php  if($partener['partener_name'] == $partener_name){echo "selected";} ?>><?php  echo $partener['partener_name'] ;?></option>
                                        <?php  } ?>
                                     </select>
                                </div>
                        
                                <div class="form-group mt-2">
                                    <label>Année edition</label>
                                    <input type="text" class="form-control" id="an" value="<?php  echo $product['an'] ; ?>" name="an" placeholder="Année edition" required>
                                </div>

                                <div class="form-group mt-2">
                                    <label>Transimission</label>
                                    <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="trans">
                                      
                                        <option  value="auto" <?php  if($product['trans'] == 'auto'){echo "selected";} ?>>Automatique</option>
                                        <option  value="manu" <?php  if($product['trans'] == 'manu'){echo "selected";} ?>>Manuelle</option>
                                     </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Autoradio</label>
                                    <select class="form-select form-select-lg mb-3" aria-label="Large select autotradio" name="car_autoradio">
                                        <option  value="usb" <?php  if($product['car_autoradio'] == 'usb'){echo "selected";} ?>>USB</option>
                                        <option  value="cd" <?php  if($product['car_autoradio'] == 'cd'){echo "selected";} ?>>CD</option>
                                     </select>
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